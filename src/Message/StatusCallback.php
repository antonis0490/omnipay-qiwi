<?php
namespace Omnipay\Qiwi\Message;

use Omnipay\Common\Message\AbstractResponse;
use Cake\Utility\Xml;

class StatusCallback extends AbstractResponse
{

    const STATUS_SUCCESSFUL = 'approved';
    const STATUS_PENDING = 'pending';

    /**
     * Construct a StatusCallback with the respective POST data.
     *
     * @param array $post post data
     */
    public function __construct(array $post)
    {
        $help = Xml::build(base64_decode($post['orderXML']));
        $this->xml = base64_decode($post['orderXML']);
        $this->order = current($help->attributes());
        $this->hash = $post['sha512'];
    }

    public function isSuccessful()
    {
        return  ($this->getStatus() == self::STATUS_SUCCESSFUL);
    }

    public function isPending()
    {
        return  ($this->getStatus() == self::STATUS_PENDING);
    }

    public function getStatus()
    {
        return mb_strtolower($this->order['status']);
    }

    public function getMessage()
    {
        return $this->order['description'].$this->order['decline_reason'];
    }

    public function getCardMask()
    {
        return $this->order['card_num'];
    }

    public function getCardHolder()
    {
        return $this->order['card_holder'];
    }

    public function IdFilled(){
        return ($this->order['id'] != '' ? true : false);
    }

    public function ValidSignature($password){
        $concat = mb_strtolower(hash('sha512', $this->xml.$password));
        $valid = $concat == mb_strtolower($this->hash);
        return $valid;
    }

}
