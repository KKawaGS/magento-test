<?php

namespace GateSoftware\GateGallery\Model;

use Magento\Framework\Model\AbstractModel;

class Gallery extends AbstractModel
{

    protected function _construct()
    {
        $this->_init(\GateSoftware\GateGallery\Model\ResourceModel\Gallery::class);
    }
}
