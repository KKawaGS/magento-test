<?php

namespace GateSoftware\GateGallery\Block\Widget;

use GateSoftware\GateGallery\Model\ResourceModel\Image\CollectionFactory as ImageFactory;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Widget\Block\BlockInterface;

class Gallery extends Template implements BlockInterface
{
    private ImageFactory $imageFactory;
    private StoreManagerInterface $storeManager;
    private UrlInterface $url;

    public function __construct(
        Template\Context      $context,
        ImageFactory          $imageFactory,
        StoreManagerInterface $storeManager,
        UrlInterface          $url,
        array                 $data = []
    )
    {
        parent::__construct($context, $data);
        $this->setTemplate('widget/gallery.phtml');
        $this->imageFactory = $imageFactory;
        $this->storeManager = $storeManager;
        $this->url = $url;
    }

    public function getGalleryImages(): string
    {
        $images = $this->imageFactory->create();
        $items = $images->addFieldToFilter('gallery_id', ['in' => [$this->getGalleryId()]])
            ->addFieldToFilter('visibility', ['in' => 1])
            ->load()
            ->getData();

        $url = $this->getMediaBaseUrl();

        foreach ($items as &$item) {
            $item['img'] = $url . $item['path'];
        }

        return json_encode($items);
    }

    private function getGalleryId()
    {
        return $this->getData('gallery_id');
    }

    private function getMediaBaseUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }
}
