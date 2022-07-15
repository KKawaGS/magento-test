<?php

namespace GateSoftware\GateGallery\Ui;

use GateSoftware\GateGallery\Model\ResourceModel\Image\CollectionFactory as ImageCollection;
use GateSoftware\GateGallery\Model\ResourceModel\Gallery\CollectionFactory as GalleryCollection;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    private RequestInterface $request;
    private GalleryCollection $galleryCollection;
    private ImageCollection $imageCollection;
    private StoreManagerInterface $storeManager;

    public function __construct($name, $primaryFieldName, $requestFieldName, GalleryCollection $galleryCollection, ImageCollection $imageCollection, StoreManagerInterface $storeManager, array $meta = [], array $data = [], RequestInterface $request)
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $galleryCollection->create();
        $this->galleryCollection = $galleryCollection;
        $this->imageCollection = $imageCollection;
        $this->storeManager = $storeManager;
        $this->request = $request;
    }

    public function getData() : array
    {
        $requestField = $this->getRequestFieldName();
        $requestFieldValue = $this->request->getParam($this->getRequestFieldName());

        $galleries = $this->galleryCollection->create()
            ->addFieldToFilter($requestField, ['in' => [$requestFieldValue]])
            ->load()
            ->getData();

        $images = $this->imageCollection->create()
            ->addFieldToFilter('gallery_id', ['in' => [$requestFieldValue]])
            ->load()
            ->getData();

        foreach ($images as &$image) {
            $image['url'] = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                . $image['path'];
        }

        if(!isset($galleries[0])) {
           $galleries[0] =[];
        }

        $loadedData[$requestFieldValue] = array_merge($galleries[0], ['image' => $images]);
        return $loadedData;
    }
}
