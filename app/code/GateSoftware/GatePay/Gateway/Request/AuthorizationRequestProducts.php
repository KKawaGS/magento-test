<?php

namespace GateSoftware\GatePay\Gateway\Request;

class AuthorizationRequestProducts extends AbstractRequest
{

    public function build(array $buildSubject): array
    {
        parent::build($buildSubject);

        return [
            'totalAmount' => number_format(
                ($this->order->getGrandTotalAmount() * 100),
                0,
                '.',
                ''
            ),
            'products' => $this->getItems()
        ];
    }

    private function getItems(): array
    {
        $items = [];

        foreach ($this->order->getItems() as $item) {
            if ($item->getProductType() == 'configurable') {
                continue;
            }

            if (($item->getParentItem())) {
                $unitPrice = $item->getParentItem()->getPrice();
            } else {
                $unitPrice = $item->getPrice();
            }

            $items[] = [
                'name' => $item->getName(),
                'unitPrice' => $unitPrice,
                'quantity' => $item->getQtyOrdered(),
                'virtual' => boolval($item->getIsVirtual())
            ];
        }

        return $items;
    }
}
