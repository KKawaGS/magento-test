<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery;

use GateSoftware\GateGallery\Model\GalleryFactory;
use GateSoftware\GateGallery\Model\ImageFactory;
use GateSoftware\GateGallery\Model\ResourceModel\Gallery as GalleryResource;
use GateSoftware\GateGallery\Model\ResourceModel\Image as ImageResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Validation\ValidationException;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Save extends Action
{
    private UploaderFactory $uploaderFactory;
    private ImageFactory $imageFactory;
    private WriteInterface $mediaDirectory;
    private GalleryFactory $galleryFactory;
    private ImageResource $imageResource;
    private GalleryResource $galleryResource;


    public function __construct(
        Context         $context,
        UploaderFactory $uploaderFactory,
        Filesystem      $filesystem,
        ImageFactory    $imageFactory,
        GalleryFactory  $galleryFactory,
        GalleryResource $galleryResource,
        ImageResource   $imageResource
    )
    {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->imageFactory = $imageFactory;
        $this->galleryFactory = $galleryFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->imageResource = $imageResource;
        $this->galleryResource = $galleryResource;
    }

    /**
     * @throws LocalizedException
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();

        try {
            if ($this->getRequest()->getMethod() !== 'POST' || !$this->_formKeyValidator->validate($this->getRequest())) {
                throw new LocalizedException(__('Invalid Request'));
            }

            $gallery = $this->galleryFactory->create();
            $gallery->setData(['name' => $params['name'], 'description' => $params['description']]);
            $this->galleryResource->save($gallery);

            try {
                foreach ($params['image'] as $imageData) {
                    $this->saveImageFile($imageData);
                    $this->saveImageToDb($imageData, $gallery->getId());
                }
            } catch (ValidationException $e) {
                throw new LocalizedException(__('Image extension is not supported.'));
            } catch (\Exception $e) {
                throw new LocalizedException(__('Image is required'));
            }

            $this->messageManager->addSuccessMessage(__('Image uploaded successfully'));

            return $this->_redirect('*/*/index');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect('*/*/upload');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            $this->messageManager->addErrorMessage(__('An error occured. Try again.'));
            return $this->_redirect('*/*/upload');
        }
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
            'path' => $this->mediaDirectory->getRelativePath('imageUploader/images') . '/' . $imageData['name']//$info['file']
        ]);

        $image->setGalleryId($galleryId);
        $this->imageResource->save($image);
    }
}
