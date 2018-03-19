<?php

namespace Omnipay\Qiwi\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractRequest;


class PaymentRequest extends AbstractRequest
{
    public $liveEndpoint = 'https://api.qiwi.com/api/v2/prv/';
    public $liveEndpoint2 = 'https://bill.qiwi.com/order/external/main.action';
    protected $sandboxEndpoint = '';
    protected $sandboxEndpoint2 = '';

    public function getReturnUrl()
    {
        return $this->getParameter('returnURL');

    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('returnURL', $value);
    }

    public function setQiwiApiId($value)
    {
        return $this->setParameter('qiwiApiId', $value);
    }

    public function setQiwiApiPass($value)
    {
        return $this->setParameter('qiwiApiPass', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notifyURL');

    }

    public function setNotifyUrl($value)
    {
        return $this->setParameter('notifyURL', $value);
    }


    public function getTransaction()
    {
        return $this->getParameter('transaction');

    }

    public function setTransaction($value)
    {
        return $this->setParameter('transaction', $value);
    }

    public function getTarget()
    {
        return $this->getParameter('target');

    }

    public function setTarget($value)
    {
        return $this->setParameter('target', $value);
    }

    public function getQiwiApiNotifyPass()
    {
        return $this->getParameter('qiwiApiNotifyPass');
    }

    public function setQiwiApiNotifyPass($value)
    {
        return $this->setParameter('qiwiApiNotifyPass', $value);
    }

    public function setQiwiWallet($value)
    {
        return $this->setParameter('qiwiWallet', $value);
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

    public function setCcy($value)
    {
        return $this->setParameter('ccy', $value);
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getShop()
    {
        return $this->getParameter('shop');
    }

    public function setShop($value)
    {
        return $this->setParameter('shop', $value);
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
        return $this->getParameter('successUrl');
    }

    public function setSuccessUrl($value)
    {
        return $this->setParameter('successUrl', $value);
    }

    public function getfailUrl()
    {
        return $this->getParameter('failUrl');
    }

    public function setfailUrl($value)
    {
        return $this->setParameter('failUrl', $value);
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

    public function setComment($value)
    {
        return $this->setParameter('comment', $value);
    }

    public function setOrderTime($value)
    {
        return $this->setParameter('orderTime', $value);
    }

    public function getOrderTime()
    {
        return $this->getParameter('orderTime');
    }

    public function setUser($value)
    {
        return $this->setParameter('user', $value);
    }

    public function setLifetime($value)
    {
        return $this->setParameter('lifetime', $value);
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


        $input = array
        (
            "amount" => $this->getAmount(),
            "ccy" => $this->getCcy(),
            "comment" => $this->getComment(),
            "user" => "tel:" . $this->getUser(),
            "lifetime" => $this->getLifetime()

        );

        return $input;
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function getCcy()
    {
        return $this->getParameter('ccy');
    }

    public function getComment()
    {
        return $this->getParameter('comment');
    }

    public function getUser()
    {
        return $this->getParameter('user');
    }

    public function getLifetime()
    {
        return $this->getParameter('lifetime');
    }

    public function getQiwiWallet()
    {
        return $this->getParameter('qiwiWallet');
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
                    'Accept' => 'application/json')
            );
        } else {
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint().$this->getQiwiWallet()."/bills/".$this->getComment(),
                array(
                    'Accept' => 'application/json'
                ),
                http_build_query($data)
            );
        }

        try {


            $httpRequest->getCurlOptions()->set(CURLOPT_SSLVERSION, 6); // CURL_SSLVERSION_TLSv1_2 for libcurl < 7.35
            $httpRequest->getCurlOptions()->set(CURLOPT_POSTFIELDS, http_build_query($data));
            $httpRequest->getCurlOptions()->set(CURLOPT_RETURNTRANSFER, true);
            $httpRequest->setAuth($this->getQiwiApiId(), $this->getQiwiApiPass());
            $httpResponse = $httpRequest->send();
            // Empty response body should be parsed also as and empty array
            $body = $httpResponse->getBody(true);
            $jsonToArrayResponse = !empty($body) ? $httpResponse->json() : array();

            if($jsonToArrayResponse['response']['result_code'] == 0 && mb_strtolower($jsonToArrayResponse['response']['bill']['status']) == 'waiting'){
                return $this->response = $this->createResponse($jsonToArrayResponse, $this->getEndpoint2());

            }
            else{
                return false;
            }

        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
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
        return 'PUT';
    }

    public function getEndpoint()
    {
        return ((bool)$this->getTestMode()) ? $this->sandboxEndpoint : $this->liveEndpoint;
    }

    /**
     * @param int $options http://php.net/manual/en/json.constants.php
     * @return string
     */
    public function toJSON($data, $options = 0)
    {
        // Because of PHP Version 5.3, we cannot use JSON_UNESCAPED_SLASHES option
        // Instead we would use the str_replace command for now.
        // TODO: Replace this code with return json_encode($this->toArray(), $options | 64); once we support PHP >= 5.4
//        if (version_compare(phpversion(), '5.4.0', '>=') === true) {
//            return json_encode($data, $options | 64);
//        }

        return $encoded = json_encode($data);

//        return str_replace('\\/', '/', json_encode($data, $options));
    }

    public function getQiwiApiId()
    {
        return $this->getParameter('qiwiApiId');

    }

    public function getQiwiApiPass()
    {
        return $this->getParameter('qiwiApiPass');

    }

    protected function createResponse($data, $endpoint)
    {
        return $this->response = new PaymentResponse($this, $data, $endpoint);
    }


//    public function sendData($data)
//    {
//        return new PaymentResponse($this, $data, $this->getEndpoint());
//    }

    public function getEndpoint2()
    {
        return ((bool)$this->getTestMode()) ? $this->sandboxEndpoint2 : $this->liveEndpoint2;
    }
}
