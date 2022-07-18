<?php

namespace GateSoftware\GateGallery\Ui\Component\Columns;

use Magento\Backend\Model\Url;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
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

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = [
                    'show' => [
                        'href' => $this->url->getUrl('gallery\gallery') . 'show' . '?' . 'id=' . $item['id'],
                        'label' => __('View')
                    ],
                    'edit' => [
                        'href' => $this->url->getUrl('gallery\gallery') . 'edit' . '?' . 'id=' . $item['id'],
                        'label' => __('Edit')
                    ],
                    'remove' => [
                        'href' => $this->url->getUrl('gallery\gallery') . 'delete' . '?' . 'id=' . $item['id'],
                        'label' => __('Remove'),
                        'confirm' => [
                            'title' => __('Delete'),
                            'message' => __('Are you sure you want to delete gallery?')
                        ]
                    ]
                ];
            }
        }
        return $dataSource;
    }
}
