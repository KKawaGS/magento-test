<?php

namespace GateSoftware\GateGuest\Model\ResourceMode\Guest;

use GateSoftware\GateGuest\Model\Guest;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            Guest::class,
            \GateSoftware\GateGuest\Model\ResourceModel\Guest::class
        );


    }
}
