<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feature
 */
class Feature
{
    /**
     * @var integer
     */
    private $featureId;

    /**
     * @var string
     */
    private $feature;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var \DateTime
     */
    private $createdDate;


    /**
     * Get featureId
     *
     * @return integer 
     */
    public function getFeatureId()
    {
        return $this->featureId;
    }

    /**
     * Set feature
     *
     * @param string $feature
     * @return Feature
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature
     *
     * @return string 
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Feature
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Feature
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
}
