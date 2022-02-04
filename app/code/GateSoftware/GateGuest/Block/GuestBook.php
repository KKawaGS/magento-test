<?php

namespace GateSoftware\GateGuest\Block;

use Magento\Framework\View\Element\Template;

class GuestBook extends Template
{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }
}
