<?php


namespace Omnipay\Omnipay\Qiwi\Message;

class RestPurchaseRequest extends PaymentRequest
{
    public function getData()
    {
        $data = parent::getData();
        return $data;
    }
}
