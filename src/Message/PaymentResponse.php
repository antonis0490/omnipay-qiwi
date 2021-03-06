<?php

namespace Omnipay\Qiwi\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;


class PaymentResponse extends AbstractResponse implements RedirectResponseInterface
{

    protected $endpoint;


    public function __construct($purchaseRequest, $data, $endpoint)
    {
        parent::__construct($purchaseRequest, $data);
        $this->endpoint = $endpoint;
    }



    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl()
    {
        return $this->endpoint;
    }

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        $dataNeeded = array("shop","transaction","successUrl","failUrl","target");
        $data = $this->request->getParameters();
        foreach ($data as $key => $value) {
            if (empty($value) || !in_array($key, $dataNeeded) ) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }
}
