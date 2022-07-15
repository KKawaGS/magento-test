<?php

namespace GateSoftware\GateGallery\Model\Config\Source;

use GateSoftware\GateGallery\Model\ResourceModel\Gallery\CollectionFactory as GalleryFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Galleries implements OptionSourceInterface
{
    private GalleryFactory $galleryFactory;

    public function __construct(GalleryFactory $galleryFactory)
    {
        $this->galleryFactory = $galleryFactory;
    }

    public function toOptionArray(): array
    {
        $result = [];
        foreach ($this->getGalleriesList() as $gallery) {
            $result[] = ['value' => $gallery['id'], 'label' => $gallery['name']];
        }

        return $result;
    }

    private function getGalleriesList(): array
    {
        $galleryFactory = $this->galleryFactory->create();
        return $galleryFactory->getData();
    }
}
