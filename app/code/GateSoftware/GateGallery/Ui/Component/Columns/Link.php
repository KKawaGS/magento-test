<?php

namespace GateSoftware\GateGallery\Ui\Component\Columns;

use Magento\Backend\Model\Url;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class Link extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected Url $url;

    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        Url                $url,
        array              $components = [],
        array              $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->url = $url;
    }

    //can be done with field action?
    public function prepareDataSource(array $dataSource): array
    {
        foreach ($dataSource['data']['items'] as &$item) {
            if (isset($item['name'])) {
                $url = $this->url->getUrl('gallery/gallery/show?' . 'id=' . $item['id']);
                $item['name'] = '<a href="' . $url . '">' . $item['name'] . '</a>';
            }
        }
        return $dataSource;
    }
}
