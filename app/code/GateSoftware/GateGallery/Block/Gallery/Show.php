<?php

namespace GateSoftware\GateGallery\Block\Gallery;

use GateSoftware\GateGallery\Model\Gallery;
use GateSoftware\GateGallery\Model\ResourceModel\Image\Collection as ImageCollection;
use GateSoftware\GateGallery\Model\ResourceModel\Image\CollectionFactory as ImageCollectionFactory;
use Magento\Backend\Model\Url;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class Show extends Template
{
    private ImageCollection $imageCollection;
    private Gallery $gallery;
    private StoreManagerInterface $storeManager;
    private Url $backendUrl;

    public function __construct(
        Template\Context       $context,
        ImageCollectionFactory $imageCollectionFactory,
        Gallery                $gallery,
        StoreManagerInterface  $storeManager,
        Url                    $backendUrl,
        array                  $data = []
    )
    {
        parent::__construct($context, $data);
        $this->imageCollection = $imageCollectionFactory->create();
        $this->gallery = $gallery;
        $this->storeManager = $storeManager;
        $this->backendUrl = $backendUrl;
    }

    public function getImagesByGalleryId(): array
    {
        $galleryId = $this->getRequest()->getParam('id');
        return $this->imageCollection
            ->addFieldToSelect('*')
            ->addFieldToFilter('gallery_id', ['in' => $galleryId])
            ->getData();
    }

    public function getGalleryData()
    {
        $galleryId = $this->getRequest()->getParam('id');
        $this->gallery->load($galleryId);
        return $this->gallery->getData();
    }

    public function getMediaBaseUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    public function getEditUrl($id): string
    {
        return $this->backendUrl->getUrl('gallery/gallery/edit/', ['id' => $id]);
    }

    public function getBackUrl(): string
    {
        return $this->backendUrl->getUrl('gallery/gallery/');
    }
}
