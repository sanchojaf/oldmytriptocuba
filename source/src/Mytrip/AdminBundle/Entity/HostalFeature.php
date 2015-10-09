<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HostalFeature
 */
class HostalFeature
{
    /**
     * @var integer
     */
    private $hostalFeatureId;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var \Mytrip\AdminBundle\Entity\Hostal
     */
    private $hostal;

    /**
     * @var \Mytrip\AdminBundle\Entity\Feature
     */
    private $feature;


    /**
     * Get hostalFeatureId
     *
     * @return integer 
     */
    public function getHostalFeatureId()
    {
        return $this->hostalFeatureId;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return HostalFeature
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime 
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set hostal
     *
     * @param \Mytrip\AdminBundle\Entity\Hostal $hostal
     * @return HostalFeature
     */
    public function setHostal(\Mytrip\AdminBundle\Entity\Hostal $hostal = null)
    {
        $this->hostal = $hostal;

        return $this;
    }

    /**
     * Get hostal
     *
     * @return \Mytrip\AdminBundle\Entity\Hostal 
     */
    public function getHostal()
    {
        return $this->hostal;
    }

    /**
     * Set feature
     *
     * @param \Mytrip\AdminBundle\Entity\Feature $feature
     * @return HostalFeature
     */
    public function setFeature(\Mytrip\AdminBundle\Entity\Feature $feature = null)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature
     *
     * @return \Mytrip\AdminBundle\Entity\Feature 
     */
    public function getFeature()
    {
        return $this->feature;
    }
}
