<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CancelDefault
 */
class CancelDefault
{
    /**
     * @var integer
     */
    private $cancelId;

    /**
     * @var integer
     */
    private $days;

    /**
     * @var integer
     */
    private $percentage;


    /**
     * Get cancelId
     *
     * @return integer 
     */
    public function getCancelId()
    {
        return $this->cancelId;
    }

    /**
     * Set days
     *
     * @param integer $days
     * @return CancelDefault
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
     * @return CancelDefault
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
}
