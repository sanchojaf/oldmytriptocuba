<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiGatway
 *
 * @ORM\Table(name="api_gateway")
 * @ORM\Entity
 */
class ApiGateway
{
    /**
     * @var integer
     *
     * @ORM\Column(name="api_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $apiId;

    /**
     * @var string
     *
     * @ORM\Column(name="gateway", type="string", length=255, nullable=false)
     */
    private $gateway;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
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
     * Set gatway
     *
     * @param string $gatway
     * @return ApiGatway
     */
    public function setGateway($gatway)
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * Get gatway
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
     * @return ApiGatway
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
