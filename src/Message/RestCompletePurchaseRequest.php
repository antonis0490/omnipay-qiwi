<?php


namespace Omnipay\Omnipay\Qiwi\Message;

class RestCompletePurchaseRequest extends PaymentCompleteRequest
{
    public function getData()
    {
        $data = parent::getToken();
        return $data;
    }
}
