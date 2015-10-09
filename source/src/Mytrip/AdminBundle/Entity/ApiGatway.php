<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiGatway
 *
 * @ORM\Table(name="api_gatway")
 * @ORM\Entity
 */
class ApiGatway
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
     * @ORM\Column(name="gatway", type="string", length=255, nullable=false)
     */
    private $gatway;

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
    public function setGatway($gatway)
    {
        $this->gatway = $gatway;

        return $this;
    }

    /**
     * Get gatway
     *
     * @return string 
     */
    public function getGatway()
    {
        return $this->gatway;
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
