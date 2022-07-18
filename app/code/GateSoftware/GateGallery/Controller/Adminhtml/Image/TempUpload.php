<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Image;

use GateSoftware\GateGallery\Model\Repository\Gallery as GalleryRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class TempUpload extends Action
{
    private GalleryRepository $galleryRepository;

    public function __construct(Context $context, GalleryRepository $galleryRepository)
    {
        parent::__construct($context);
        $this->galleryRepository = $galleryRepository;
    }

    public function execute()
    {
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $result = $this->galleryRepository->saveTempImage('image');
            return $jsonResult->setData($result);
        } catch (LocalizedException $e) {
            return $jsonResult->setData(['errorcode' => 0, 'error' => $e->getMessage()]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            return $jsonResult->setData(['errorcode' => 0, 'error' => __('An error occured, try later')]);
        }
    }
}
