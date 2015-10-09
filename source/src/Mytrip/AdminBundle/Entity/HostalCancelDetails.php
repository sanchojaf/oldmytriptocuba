<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HostalCancelDetails
 */
class HostalCancelDetails
{
    /**
     * @var integer
     */
    private $hostalCancelId;

    /**
     * @var integer
     */
    private $days;

    /**
     * @var integer
     */
    private $percentage;

    /**
     * @var \Mytrip\AdminBundle\Entity\Hostal
     */
    private $hostal;


    /**
     * Get hostalCancelId
     *
     * @return integer 
     */
    public function getHostalCancelId()
    {
        return $this->hostalCancelId;
    }

    /**
     * Set days
     *
     * @param integer $days
     * @return HostalCancelDetails
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return integer 
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set percentage
     *
     * @param integer $percentage
     * @return HostalCancelDetails
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * Get percentage
     *
     * @return integer 
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * Set hostal
     *
     * @param \Mytrip\AdminBundle\Entity\Hostal $hostal
     * @return HostalCancelDetails
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
}
