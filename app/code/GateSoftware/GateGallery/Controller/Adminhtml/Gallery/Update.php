<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery;

use GateSoftware\GateGallery\Model\Repository\Gallery as GalleryRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;


class Update extends Action
{
    private GalleryRepository $galleryRepository;

    public function __construct(
        Context           $context,
        GalleryRepository $galleryRepository
    )
    {
        parent::__construct($context);
        $this->galleryRepository = $galleryRepository;
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $galleryId = $this->getRequest()->getParam('id');
        $editImages = $this->getRequest()->getParam('image') ?? [];
        $images = $this->galleryRepository->getImages($galleryId);

        try {
            if ($this->getRequest()->getMethod() !== 'POST' || !$this->_formKeyValidator->validate($this->getRequest())) {
                throw new LocalizedException(__('Invalid Request'));
            }

            if (!isset($params['name']) || !isset($params['description'])) {
                throw new LocalizedException(__('Required parameter missing'));
            }

            $this->galleryRepository->saveGallery([
                'name' => $params['name'],
                'description' => $params['description'],
                'id' => $galleryId
            ]);

            //find diff between db images and after edit array
            foreach ($images as $image) {
                $flag = false;
                foreach ($editImages as $key => $editImage) {
                    if ($image->getId() == $editImage['id']) {
                        $this->galleryRepository->saveImageIfChanged($editImage);
                        unset($editImages[$key]);
                        $flag = true;
                        break;
                    }
                }
                //id not found in edit array so it will be deleted
                if (!$flag) {
                    $this->galleryRepository->deleteImageById($image->getId());
                }
            }

            //resulting array consist of new images
            foreach ($editImages as $imageData) {
                $this->galleryRepository->saveImage($imageData, $galleryId);
            }

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect('*/*/edit/id/' . $galleryId);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            $this->messageManager->addErrorMessage(__('An error occured. Try again.'));
            return $this->_redirect('*/*/edit/id/' . $galleryId);
        }

        $this->messageManager->addSuccessMessage(__('Gallery successfully updated'));
        return $this->_redirect('*/*/index');
    }
}
