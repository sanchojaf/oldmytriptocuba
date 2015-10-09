<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiGateway
 */
class ApiGateway
{
    /**
     * @var integer
     */
    private $apiId;

    /**
     * @var string
     */
    private $gateway;

    /**
     * @var string
     */
    private $status;


    /**
     * Get apiId
     *
     * @return integer 
     */
    public function getApiId()
    {
        return $this->apiId;
    }

    /**
     * Set gateway
     *
     * @param string $gateway
     * @return ApiGateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * Get gateway
     *
     * @return string 
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return ApiGateway
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
