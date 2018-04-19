# Qiwi: Qiwi

**Qiwi driver for the Qiwi PHP payment processing library**

## Installation

Qiwi is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "antonis0490/omnipay-qiwi": "dev-master"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

To make a request:

    use Omnipay\Omnipay;
    use Omnipay\Qiwi\Message\StatusCallback;

    $gateway = Omnipay::create('Qiwi');


    $gateway->initialize(array(
        'qiwiApiId' => "",
        'qiwiApiPass' => "",
        'qiwiApiNotifyPass' => "", 
        'qiwiWallet' => qiwi Wallet,
        'testMode' => 1, // Or false when you are ready for live transactions
    ));
    
    $options = array
    (

        'user' => "Users Phone number",
        'comment' => "",
        'ccy' => "currency",
        'amount' => "",
        "lifetime" => "how long will this be valid? data here",
        "shop" => "qiwi Wallet",
        "transaction" => "transaction id",
        "target" => "",
        'successUrl' => "",
        'failUrl' => "",
        'cancel_url' => "",

    );
    
    $transaction = $gateway->purchase($options);
    $response = $transaction->send();

    if (gettype($response) == "object" && $response->isRedirect()) {
        //redirect
    } else {
       //error
    }
  
    
Notify function:

    $status = new StatusCallback($_REQUEST);

    if ($status->UserFilled()) {
        //do whats needed
    }

    //reply with 200
    $this->response->statusCode(200);
    $this->response->type("text/xml");

    $dom = new \DOMDocument('1.0');
    $root = $dom->createElement('result');
    $dom->appendChild($root);
    $root->appendChild( $dom->createElement('result_code', 0) );
    $dom->formatOutput = false;
    $reply_xml = $dom->saveXML();


    $this->response->body($reply_xml);
    return $this->response;
    
The following gateways are provided by this package:

* Qiwi

For general usage instructions, please see the main [Omnipay](https://omnipay.thephpleague.com/)
site.
