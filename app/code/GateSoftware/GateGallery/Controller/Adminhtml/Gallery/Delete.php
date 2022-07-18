<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery;

use GateSoftware\GateGallery\Model\Repository\Gallery as GalleryRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action
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
        try {
            $galleryId = $this->getRequest()->getParam('id');
            $this->galleryRepository->deleteGalleryById($galleryId);
            $this->messageManager->addSuccessMessage(__('Gallery successfully deleted'));

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
