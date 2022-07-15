<?php

namespace GateSoftware\GateGallery\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Gallery extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('gategallery_gallery', 'id');
    }
}
