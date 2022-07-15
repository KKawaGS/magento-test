<?php

namespace GateSoftware\GateGallery\Model\ResourceModel\Image;

use GateSoftware\GateGallery\Model\Image;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(Image::class, \GateSoftware\GateGallery\Model\ResourceModel\Image::class);
    }
}
