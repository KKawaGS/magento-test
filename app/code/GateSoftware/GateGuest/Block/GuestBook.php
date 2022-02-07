<?php

namespace GateSoftware\GateGuest\Block;

use Magento\Framework\View\Element\Template;

class GuestBook extends Template
{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function getFormPostAction() : string
    {
        return '/guestbook/index/post';
    }

    public function getGuestFormLink() : string
    {
        return '/guestbook';
    }
}
