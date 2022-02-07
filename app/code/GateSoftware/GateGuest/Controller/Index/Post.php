<?php

namespace GateSoftware\GateGuest\Controller\Index;

use GateSoftware\GateGuest\Model\ResourceModel\Guest;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use GateSoftware\GateGuest\Model\GuestFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Message\Manager;


class Post implements HttpPostActionInterface
{
    protected GuestFactory $guestFactory;
    protected Guest $guestResource;
    protected RemoteAddress $remoteAddress;
    protected Manager $messageManager;
    protected RequestInterface $request;
    protected ResultFactory $resultFactory;
    protected RedirectInterface $redirect;

    public function __construct(
        GuestFactory $guestFactory,
        RemoteAddress $remoteAddress,
        Manager $messageManager,
        RequestInterface $request,
        ResultFactory $resultFactory,
        RedirectInterface $redirect,
        Guest $guestResource
    )
    {
        $this->guestFactory = $guestFactory;
        $this->remoteAddress = $remoteAddress;
        $this->messageManager = $messageManager;
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->redirect = $redirect;
        $this->guestResource = $guestResource;
    }

    public function execute() : ResultInterface
    {
        try {
            $data = (array)$this->request->getPost();
            if(!empty($data)) {
                $data['ip'] = $this->remoteAddress->getRemoteAddress();
                $guestModel = $this->guestFactory->create()->setData($data);
                $this->guestResource->save($guestModel);
                $this->messageManager->addSuccessMessage(__('Saved!'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e, __('Saving error!'));
        }

        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setUrl($this->redirect->getRefererUrl());

        return $redirect;
    }
}
