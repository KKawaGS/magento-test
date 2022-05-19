<?php

namespace GateSoftware\GateGallery\Controller\Adminhtml\Gallery\Index;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
