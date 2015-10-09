<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 */
class Settings
{
    /**
     * @var integer
     */
    private $settingId;

    /**
     * @var float
     */
    private $reservationCharge;

    /**
     * @var float
     */
    private $bookingPercentage;

    /**
     * @var integer
     */
    private $bookingConfirmationDays;


    /**
     * Get settingId
     *
     * @return integer 
     */
    public function getSettingId()
    {
        return $this->settingId;
    }

    /**
     * Set reservationCharge
     *
     * @param float $reservationCharge
     * @return Settings
     */
    public function setReservationCharge($reservationCharge)
    {
        $this->reservationCharge = $reservationCharge;

        return $this;
    }

    /**
     * Get reservationCharge
     *
     * @return float 
     */
    public function getReservationCharge()
    {
        return $this->reservationCharge;
    }

    /**
     * Set bookingPercentage
     *
     * @param float $bookingPercentage
     * @return Settings
     */
    public function setBookingPercentage($bookingPercentage)
    {
        $this->bookingPercentage = $bookingPercentage;

        return $this;
    }

    /**
     * Get bookingPercentage
     *
     * @return float 
     */
    public function getBookingPercentage()
    {
        return $this->bookingPercentage;
    }

    /**
     * Set bookingConfirmationDays
     *
     * @param integer $bookingConfirmationDays
     * @return Settings
     */
    public function setBookingConfirmationDays($bookingConfirmationDays)
    {
        $this->bookingConfirmationDays = $bookingConfirmationDays;

        return $this;
    }

    /**
     * Get bookingConfirmationDays
     *
     * @return integer 
     */
    public function getBookingConfirmationDays()
    {
        return $this->bookingConfirmationDays;
    }
}
