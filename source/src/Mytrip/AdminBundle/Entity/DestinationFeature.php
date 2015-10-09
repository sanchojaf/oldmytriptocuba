<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DestinationFeature
 */
class DestinationFeature
{
    /**
     * @var integer
     */
    private $distinationFeatureId;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var \Mytrip\AdminBundle\Entity\Destination
     */
    private $destination;

    /**
     * @var \Mytrip\AdminBundle\Entity\Feature
     */
    private $feature;


    /**
     * Get distinationFeatureId
     *
     * @return integer 
     */
    public function getDistinationFeatureId()
    {
        return $this->distinationFeatureId;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return DestinationFeature
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
     * Set destination
     *
     * @param \Mytrip\AdminBundle\Entity\Destination $destination
     * @return DestinationFeature
     */
    public function setDestination(\Mytrip\AdminBundle\Entity\Destination $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return \Mytrip\AdminBundle\Entity\Destination 
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set feature
     *
     * @param \Mytrip\AdminBundle\Entity\Feature $feature
     * @return DestinationFeature
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
