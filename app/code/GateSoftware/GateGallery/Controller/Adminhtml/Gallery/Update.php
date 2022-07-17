<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery;

use GateSoftware\GateGallery\Model\Gallery;
use GateSoftware\GateGallery\Model\GalleryFactory;
use GateSoftware\GateGallery\Model\ImageFactory;
use GateSoftware\GateGallery\Model\ResourceModel\Gallery as GalleryResource;
use GateSoftware\GateGallery\Model\ResourceModel\Image as ImageResource;
use GateSoftware\GateGallery\Model\ResourceModel\Image\Collection as ImageCollection;
use GateSoftware\GateGallery\Model\ResourceModel\Image\CollectionFactory as ImageCollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;


class Update extends Action
{

    private Gallery $gallery;
    private GalleryResource $galleryResource;
    private ImageCollection $imageCollection;
    private ImageFactory $imageFactory;
    private ImageResource $imageResource;
    private UploaderFactory $uploaderFactory;
    private WriteInterface $mediaDirectory;

    public function __construct(
        Context                $context,
        ImageCollectionFactory $imageCollectionFactory,
        ImageResource          $imageResource,
        ImageFactory           $imageFactory,
        GalleryFactory         $galleryFactory,
        GalleryResource        $galleryResource,
        UploaderFactory        $uploaderFactory,
        Filesystem             $filesystem
    )
    {
        parent::__construct($context);
        $this->gallery = $galleryFactory->create();
        $this->imageCollection = $imageCollectionFactory->create();
        $this->imageResource = $imageResource;
        $this->imageFactory = $imageFactory;
        $this->galleryResource = $galleryResource;
        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $idValue = $this->getRequest()->getParam('id');
        $editImages = $this->getRequest()->getParam('image') ?? [];
        $this->galleryResource->load($this->gallery, $idValue);
        $images = $this->imageCollection->addFieldToSelect('*')
            ->addFieldToFilter('gallery_id', ['in' => $idValue])
            ->getItems();

        try {
            if ($this->getRequest()->getMethod() !== 'POST' || !$this->_formKeyValidator->validate($this->getRequest())) {
                throw new LocalizedException(__('Invalid Request'));
            }

            if (!isset($params['name']) || !isset($params['description'])) {
                throw new LocalizedException(__('Required parameter missing'));
            }

            $this->gallery->setData('name', $params['name']);
            $this->gallery->setData('description', $params['description']);

            if ($this->gallery->hasDataChanges()) {
                $this->galleryResource->save($this->gallery);
            }

            //find diff between db images and after edit array
            foreach ($images as $image) {
                $flag = false;
                foreach ($editImages as $key => $editImage) {
                    if ($image->getId() == $editImage['id']) {
                        //id is in the array - nothing has changed
                        unset($editImages[$key]);
                        $flag = true;
                        break;
                    }
                }
                //id not found in edit array so it will be deleted
                if (!$flag) {
                    $this->imageResource->delete($image);
                }
            }

            //resulting array consist only of new images
            foreach ($editImages as $imageData) {
                $imgInfo = $this->saveImageFile($imageData);
                $this->saveImageToDb($imageData, $this->gallery->getId());
            }

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect('*/*/edit/id/' . $idValue);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            $this->messageManager->addErrorMessage(__('An error occured. Try again.'));
            return $this->_redirect('*/*/edit/id/' . $idValue);
        }

        $this->messageManager->addSuccessMessage(__('Gallery successfully updated'));
        return $this->_redirect('*/*/index');
    }

    private function saveImageFile(array $imageData): array
    {
        if (!file_exists($imageData['tmp_name'])) {
            $imageData['tmp_name'] = $imageData['path'] . '/' . $imageData['file'];
        }

        $fileUploader = $this->uploaderFactory->create(['fileId' => $imageData]);
        $fileUploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
        $fileUploader->setAllowRenameFiles(true);
        $fileUploader->setAllowCreateFolders(true);
        $fileUploader->validateFile();

        return $fileUploader->save($this->mediaDirectory->getAbsolutePath('imageUploader/images'));
    }

    private function saveImageToDb(array $imageData, $galleryId)
    {
        $image = $this->imageFactory->create();
        $image->setData([
            'name' => $imageData['name'],
            'type' => $imageData['type'],
            'size' => $imageData['size'],
            'previewType' => $imageData['previewType'],
            'path' => $this->mediaDirectory->getRelativePath('imageUploader/images') . $imageData['file']//$info['file']
        ]);

        $image->setGalleryId($galleryId);
        $this->imageResource->save($image);
    }
}
