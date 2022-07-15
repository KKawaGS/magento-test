<?php

namespace GateSoftware\GateGallery\Ui\Component\Columns;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    private StoreManagerInterface $storeManagerInterface;

    public function __construct(
        ContextInterface      $context,
        UiComponentFactory    $uiComponentFactory,
        StoreManagerInterface $storeManagerInterface,
        array                 $components = [],
        array                 $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManagerInterface = $storeManagerInterface;
    }

    public function prepareDataSource(array $dataSource): array
    {
        foreach ($dataSource["data"]['items'] as &$item) {
            if (isset($item["path"])) {
                $url = $this->storeManagerInterface->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $item["path"];
                $item["path_src"] = $url;
                $item["path_alt"] = $item["id"];
                $item["path_link"] = $url;
                $item["path_orig_src"] = $url;
            }
        }
        return $dataSource;
    }
}
