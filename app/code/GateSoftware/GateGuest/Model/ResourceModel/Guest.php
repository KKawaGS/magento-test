<?php

namespace GateSoftware\GateGuest\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Guest extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('gate_guest_book', 'id');
    }
}
