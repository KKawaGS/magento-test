<?php

namespace GateSoftware\GateGallery\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Image extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'GateGallery_images';

    public function getIdentities(): array
    {
        return [
            self::CACHE_TAG . '_' . $this->getId()
        ];
    }

    protected function _construct()
    {
        $this->_init(\GateSoftware\GateGallery\Model\ResourceModel\Image::class);
    }
}
