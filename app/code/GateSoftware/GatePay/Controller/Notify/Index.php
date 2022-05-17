<?php

namespace GateSoftware\GatePay\Controller\Notify;


use Exception;
use GateSoftware\GatePay\PayU\PayUAuthorization;
use GateSoftware\GatePay\PayU\PayUNotification;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DB\Transaction;
use Magento\Payment\Model\Method\Logger;
use Magento\Sales\Model\Order\Payment;
use Magento\Sales\Model\OrderRepository;

/*
 * Process PayU notification
 */
class Index implements HttpGetActionInterface, HttpPostActionInterface, CsrfAwareActionInterface
{
    private ResultFactory $resultFactory;
    private OrderRepository $orderRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private Transaction $transaction;
    private Logger $logger;
    private PayUNotification $paynotification;


    public function __construct(
        ResultFactory                   $result,
        OrderRepository                 $orderRepository,
        Transaction                     $transaction,
        Logger                          $logger,
        PayUAuthorization               $payUAuth,
        SearchCriteriaBuilder           $searchCriteriaBuilder,
        PayUNotification                $payunotification
    )
    {
        $this->resultFactory = $result;
        $this->orderRepository = $orderRepository;
        $this->transaction = $transaction;
        $this->logger = $logger;
        $this->payUAuth = $payUAuth;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->paynotification = $payunotification;
    }

    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $this->paynotification->validateNotification();

        } catch (Exception $e) {
            return $result
                ->setData($e->getMessage())
                ->setHttpResponseCode($e->getCode());
        }

        if ($this->paynotification->getStatus() == PayUNotification::PAYU_STATUS_PENDING) {
            return $result
                ->setData(["message" => "Payment pending", "code" => 200])
                ->setHttpResponseCode(200);
        }

        $extOrderId = $this->paynotification->getNotificationBody()->order->extOrderId;
        $order = $this->findOrder($extOrderId);

        if ($order->isEmpty()) {
            return $result
                ->setData(["message" => "There is no order with this id", "code" => 400])
                ->setHttpResponseCode(400);
        }

        /* @var Payment $payment */
        $payment = $order->getPayment();
        if (!$payment->canCapture()) {
            return $result
                ->setData(["message" => "Payment already captured", "code" => 200])
                ->setHttpResponseCode(200);
        }

        if ($this->paynotification->getStatus() == PayUNotification::PAYU_STATUS_CANCELED) {
            $payment->void($payment);
            $order->cancel();
            $this->transaction->addObject($order)->save();

            $this->logger->debug(['void'], null, true);

            return $result
                ->setData(["message" => "payment canceled", "code" => 200])
                ->setHttpResponseCode(200);
        }

        $capturedPayment = $payment->capture();
        $invoice = $capturedPayment->getOrder()->getInvoiceCollection()->getFirstItem();
        $this->logger->debug(['captured'], null, true);
        $this->transaction->addObject($invoice)->addObject($invoice->getOrder())->save();

        return $result->setHttpResponseCode(200);
    }

    private function findOrder($extOrderId): \Magento\Framework\DataObject
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $extOrderId)
            ->create();

        return $this->orderRepository->getList($searchCriteria)->getLastItem();
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
