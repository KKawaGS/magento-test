<?php

namespace GateSoftware\GateGallery\Ui\Component\Form;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{
    private UrlInterface $urlInterface;

    public function __construct(UrlInterface $urlInterface)
    {
        $this->urlInterface = $urlInterface;
    }

    public function getButtonData(): array
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    private function getBackUrl(): string
    {
        return $this->urlInterface->getUrl('*/*/');
    }
}
