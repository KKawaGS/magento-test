<?php

namespace GateSoftware\GatePay\Controller\Redirect;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;

/*
 * Redirect to payment page
 */
class Payment implements HttpGetActionInterface
{
    private ResponseInterface $redirect;
    private Session $session;

    public function __construct(
        ResponseInterface $redirect,
        Session $session
    )
    {
        $this->redirect = $redirect;
        $this->session = $session;
    }

    public function execute()
    {
        $this->redirect->setRedirect($this->session->getLastRealOrder()->getPayment()->getAdditionalInformation('payu_redirect_url'))->sendResponse();
    }
}
