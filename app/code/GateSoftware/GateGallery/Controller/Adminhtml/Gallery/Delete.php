<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery;

use GateSoftware\GateGallery\Model\GalleryFactory;
use GateSoftware\GateGallery\Model\ResourceModel\Gallery;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action
{

    private Gallery $galleryResource;
    private GalleryFactory $galleryFactory;

    public function __construct(
        Context        $context,
        Gallery        $galleryResource,
        GalleryFactory $galleryFactory
    )
    {
        parent::__construct($context);
        $this->galleryFactory = $galleryFactory;
        $this->galleryResource = $galleryResource;
    }

    public function execute()
    {
        try {
            $galleryId = $this->getRequest()->getParam('id');
            $gallery = $this->galleryFactory->create();
            $this->galleryResource->load($gallery, $galleryId);

            if (!$gallery->hasData()) {
                throw new LocalizedException(__('Gallery doesn\'t exist'));
            }

            $this->messageManager->addSuccessMessage(__('Gallery successfully deleted'));
            $this->galleryResource->delete($gallery);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            $this->messageManager->addErrorMessage(__('An error occurred. Try again.'));
        }

        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirect->setPath('*/*/index');
    }
}
