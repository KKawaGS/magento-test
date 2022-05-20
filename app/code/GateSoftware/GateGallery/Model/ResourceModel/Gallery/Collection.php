<?php

namespace GateSoftware\GateGallery\Model\ResourceModel\Gallery;

use GateSoftware\GateGallery\Model\Gallery;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function __construct()
    {
        $this->_init(
            Gallery::class,
            \GateSoftware\GateGallery\Model\ResourceModel\Gallery::class
        );
    }
}
