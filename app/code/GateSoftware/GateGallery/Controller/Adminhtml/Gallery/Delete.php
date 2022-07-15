<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery;

use GateSoftware\GateGallery\Model\GalleryFactory;
use GateSoftware\GateGallery\Model\ResourceModel\Gallery;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

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
        if ($this->getRequest()->getActionName() === 'delete') {
            //todo check if there is gallery to delete?
            $galleryId = $this->getRequest()->getParam('id');
            $gallery = $this->galleryFactory->create();
            $gallery->setId($galleryId);
            $this->galleryResource->delete($gallery);
        }

        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirect->setPath('*/*/index');
    }
}
