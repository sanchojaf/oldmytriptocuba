<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiInfo
 */
class ApiInfo
{
    /**
     * @var integer
     */
    private $apiInfoId;

    /**
     * @var string
     */
    private $metaKey;

    /**
     * @var string
     */
    private $metaValue;

    /**
     * @var \Mytrip\AdminBundle\Entity\ApiGateway
     */
    private $api;


    /**
     * Get apiInfoId
     *
     * @return integer 
     */
    public function getApiInfoId()
    {
        return $this->apiInfoId;
    }

    /**
     * Set metaKey
     *
     * @param string $metaKey
     * @return ApiInfo
     */
    public function setMetaKey($metaKey)
    {
        $this->metaKey = $metaKey;

        return $this;
    }

    /**
     * Get metaKey
     *
     * @return string 
     */
    public function getMetaKey()
    {
        return $this->metaKey;
    }

    /**
     * Set metaValue
     *
     * @param string $metaValue
     * @return ApiInfo
     */
    public function setMetaValue($metaValue)
    {
        $this->metaValue = $metaValue;

        return $this;
    }

    /**
     * Get metaValue
     *
     * @return string 
     */
    public function getMetaValue()
    {
        return $this->metaValue;
    }

    /**
     * Set api
     *
     * @param \Mytrip\AdminBundle\Entity\ApiGateway $api
     * @return ApiInfo
     */
    public function setApi(\Mytrip\AdminBundle\Entity\ApiGateway $api = null)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * Get api
     *
     * @return \Mytrip\AdminBundle\Entity\ApiGateway 
     */
    public function getApi()
    {
        return $this->api;
    }
}
