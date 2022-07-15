<?php

namespace GateSoftware\GateGallery\Ui\Component\Form;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class UploadButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label' => __('Upload'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage_init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
