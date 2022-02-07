<?php

namespace GateSoftware\GateGuest\Model;

use Magento\Framework\Model\AbstractModel;

class Guest extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\GateSoftware\GateGuest\Model\ResourceModel\Guest::class);
    }
}
