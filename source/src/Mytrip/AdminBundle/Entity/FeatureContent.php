<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeatureContent
 */
class FeatureContent
{
    /**
     * @var integer
     */
    private $featureContentId;

    /**
     * @var string
     */
    private $feature;

    /**
     * @var string
     */
    private $lan;

    /**
     * @var \Mytrip\AdminBundle\Entity\Feature
     */
    private $feature2;


    /**
     * Get featureContentId
     *
     * @return integer 
     */
    public function getFeatureContentId()
    {
        return $this->featureContentId;
    }

    /**
     * Set feature
     *
     * @param string $feature
     * @return FeatureContent
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
     * Set lan
     *
     * @param string $lan
     * @return FeatureContent
     */
    public function setLan($lan)
    {
        $this->lan = $lan;

        return $this;
    }

    /**
     * Get lan
     *
     * @return string 
     */
    public function getLan()
    {
        return $this->lan;
    }

    /**
     * Set feature2
     *
     * @param \Mytrip\AdminBundle\Entity\Feature $feature2
     * @return FeatureContent
     */
    public function setFeature2(\Mytrip\AdminBundle\Entity\Feature $feature2 = null)
    {
        $this->feature2 = $feature2;

        return $this;
    }

    /**
     * Get feature2
     *
     * @return \Mytrip\AdminBundle\Entity\Feature 
     */
    public function getFeature2()
    {
        return $this->feature2;
    }
}
