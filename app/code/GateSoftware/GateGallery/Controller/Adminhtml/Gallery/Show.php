<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery;

use Magento\Framework\Controller\ResultFactory;

class Show extends \Magento\Backend\App\Action
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->getConfig()->getTitle()->set('Gate Gallery');
        return $page;
    }
}
