<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentSecurityToken
 */
class PaymentSecurityToken
{
    /**
     * @var integer
     */
    private $token;

    /**
     * @var \stdClass
     */
    private $details;

    /**
     * @var string
     */
    private $afterUrl;

    /**
     * @var string
     */
    private $targetUrl;

    /**
     * @var string
     */
    private $paymentName;

    /**
     * @var string
     */
    private $hash;


    /**
     * Get token
     *
     * @return integer 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set details
     *
     * @param \stdClass $details
     * @return PaymentSecurityToken
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return \stdClass 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set afterUrl
     *
     * @param string $afterUrl
     * @return PaymentSecurityToken
     */
    public function setAfterUrl($afterUrl)
    {
        $this->afterUrl = $afterUrl;

        return $this;
    }

    /**
     * Get afterUrl
     *
     * @return string 
     */
    public function getAfterUrl()
    {
        return $this->afterUrl;
    }

    /**
     * Set targetUrl
     *
     * @param string $targetUrl
     * @return PaymentSecurityToken
     */
    public function setTargetUrl($targetUrl)
    {
        $this->targetUrl = $targetUrl;

        return $this;
    }

    /**
     * Get targetUrl
     *
     * @return string 
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * Set paymentName
     *
     * @param string $paymentName
     * @return PaymentSecurityToken
     */
    public function setPaymentName($paymentName)
    {
        $this->paymentName = $paymentName;

        return $this;
    }

    /**
     * Get paymentName
     *
     * @return string 
     */
    public function getPaymentName()
    {
        return $this->paymentName;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return PaymentSecurityToken
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }
}
