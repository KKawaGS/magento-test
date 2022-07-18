<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery;

use GateSoftware\GateGallery\Model\Repository\Gallery as GalleryRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validation\ValidationException;

class Save extends Action
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

            $gallery = $this->galleryRepository->saveGallery($params);

            try {
                foreach ($params['image'] as $imageData) {
                    $this->galleryRepository->saveImage($imageData, $gallery->getId());
                }
            } catch (ValidationException $e) {
                throw new LocalizedException(__('Image extension is not supported.'));
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

}
