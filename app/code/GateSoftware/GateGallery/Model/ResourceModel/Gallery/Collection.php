<?php

namespace GateSoftware\GateGallery\Model\ResourceModel\Gallery;

use GateSoftware\GateGallery\Model\Gallery;
use GateSoftware\GateGallery\Model\ResourceModel\Gallery as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            Gallery::class,
            ResourceModel::class
        );
    }
}
