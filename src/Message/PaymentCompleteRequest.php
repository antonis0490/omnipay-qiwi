<?php

namespace Omnipay\Qiwi\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\InvalidResponseException;


class PaymentCompleteRequest extends AbstractRequest
{
    public $liveEndpoint = 'https://bill.qiwi.com/order/external/main.action';
    protected $sandboxEndpoint = '';

    public function getEndpoint()
    {
        return ((bool)$this->getTestMode()) ? $this->sandboxEndpoint : $this->liveEndpoint;
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnURL');

    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('returnURL', $value);
    }

    public function getName()
    {
        return $this->getParameter('name');

    }

    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    public function getLname()
    {
        return $this->getParameter('lname');

    }

    public function setLname($value)
    {
        return $this->setParameter('lname', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notifyURL');

    }

    public function setNotifyUrl($value)
    {
        return $this->setParameter('notifyURL', $value);
    }


    public function getClientId()
    {
        return $this->getParameter('clientId');
    }


    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getNumber()
    {
        return $this->getParameter('number');
    }

    public function setNumber($value)
    {
        return $this->setParameter('number', $value);
    }

    public function getDescription()
    {
        return $this->getParameter('description');
    }

    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getCartId()
    {
        return $this->getParameter('cartId');
    }

    public function setCartId($value)
    {
        return $this->setParameter('cartId', $value);
    }

    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    public function setLocale($value)
    {
        $supported = array('en', 'ru', 'zh', 'ja');
        if (!in_array($value, $supported)) {
            $value = 'en';
        }
        return $this->setParameter('locale', $value);
    }

    public function getSuccessUrl()
    {
        return $this->getParameter('success_url');
    }

    public function setSuccessUrl($value)
    {
        return $this->setParameter('success_url', $value);
    }

    public function getDeclineUrl()
    {
        return $this->getParameter('decline_url');
    }

    public function setDeclineUrl($value)
    {
        return $this->setParameter('decline_url', $value);
    }

    public function getCancelUrl()
    {
        return $this->getParameter('cancel_url');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancel_url', $value);
    }

    public function getHeader()
    {
        return $this->getParameter('header');
    }

    public function setHeader($value)
    {
        return $this->setParameter('header', $value);
    }

    public function getChannelCode()
    {
        return $this->getParameter('channelCode');
    }

    public function setChannelCode($value)
    {
        return $this->setParameter('channelCode', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }


    public function setOrderTime($value)
    {
        return $this->setParameter('orderTime', $value);
    }

    public function getOrderTime()
    {
        return $this->getParameter('orderTime');
    }


    public function getToken()
    {
        $input = array
        (

            "token" => $this->getParameter('token')


        );
        return $input;
    }


    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }


    public function getData()
    {

        $concat = $this->getCartId() . ";" . $this->getAmount() . ";" . $this->getCurrency() . ";" . $this->getClientId() . ";" . $this->getHeader();
        $concat = hash("sha256", $concat);

        $sign = crypt($concat, $this->getSecret());
        $pos = mb_strpos($sign, $this->getSecret()) + mb_strlen($this->getSecret());
        $signature = mb_substr($sign, $pos);

        $input = array
        (
            "header" => array
            (
                "version" => $this->getHeader(),
                "merchantCode" => $this->getClientId(),
                "signature" => $signature
            ),
            "body" => array
            (
                "channelCode" => "BANK_TRANSFER",
                "notifyURL" => $this->getNotifyUrl(),
                "returnURL" => $this->getReturnUrl(),
                "orderAmount" => $this->getAmount(),
                "orderTime" => $this->getOrderTime(),
                "cartId" => $this->getCartId(),
                "currency" => $this->getCurrency(),
                "customerInfo" => array
                (
                    "address" => array
                    (
                        "email" => $this->getEmail()
                    ),
                    "cardHolderFirstName" => $this->getName(),
                    "cardHolderLastName" => $this->getLname()
                )
            )
        );

        $encoded = json_encode($input);

        return $input;
    }


    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    protected function createResponse($data)
    {
        return $this->response = new PaymentResponse($this, $data, $this->getEndpoint());
    }

    /**
     * @param int $options http://php.net/manual/en/json.constants.php
     * @return string
     */
    public function toJSON($data, $options = 0)
    {

        return $encoded = json_encode($data);

    }



    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        // Guzzle HTTP Client createRequest does funny things when a GET request
        // has attached data, so don't send the data if the method is GET.
        if ($this->getHttpMethod() == 'GET') {
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint() . '?' . http_build_query($data),
                array(
                    'Content-type' => 'application/json',
                )
            );
        } else {

            $token = "token=".$data['token'];
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                array(
                    'Content-type' => 'application/x-www-form-urlencoded',
                ),
                $token
            );
        }

        try {
//            $httpRequest->getCurlOptions()->set(CURLOPT_POSTFIELDS, $data );
//            $httpRequest->getCurlOptions()->set(CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded') );
            $httpResponse = $httpRequest->send();
            // Empty response body should be parsed also as and empty array
            $body = $httpResponse->getBody(true);
            return $this->response = $this->createResponse($httpResponse);
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );

        }

    }
}
