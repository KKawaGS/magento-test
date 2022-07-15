<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Edit extends Action
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
