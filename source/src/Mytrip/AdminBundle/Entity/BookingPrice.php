<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingPrice
 */
class BookingPrice
{
    /**
     * @var integer
     */
    private $bookingPriceId;

    /**
     * @var float
     */
    private $totalPrice;

    /**
     * @var string
     */
    private $defaultCurrency;

    /**
     * @var float
     */
    private $conversionRate;

    /**
     * @var float
     */
    private $conversionPrice;

    /**
     * @var string
     */
    private $conversionCurrency;

    /**
     * @var \Mytrip\AdminBundle\Entity\Booking
     */
    private $booking;


    /**
     * Get bookingPriceId
     *
     * @return integer 
     */
    public function getBookingPriceId()
    {
        return $this->bookingPriceId;
    }

    /**
     * Set totalPrice
     *
     * @param float $totalPrice
     * @return BookingPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return float 
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set defaultCurrency
     *
     * @param string $defaultCurrency
     * @return BookingPrice
     */
    public function setDefaultCurrency($defaultCurrency)
    {
        $this->defaultCurrency = $defaultCurrency;

        return $this;
    }

    /**
     * Get defaultCurrency
     *
     * @return string 
     */
    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }

    /**
     * Set conversionRate
     *
     * @param float $conversionRate
     * @return BookingPrice
     */
    public function setConversionRate($conversionRate)
    {
        $this->conversionRate = $conversionRate;

        return $this;
    }

    /**
     * Get conversionRate
     *
     * @return float 
     */
    public function getConversionRate()
    {
        return $this->conversionRate;
    }

    /**
     * Set conversionPrice
     *
     * @param float $conversionPrice
     * @return BookingPrice
     */
    public function setConversionPrice($conversionPrice)
    {
        $this->conversionPrice = $conversionPrice;

        return $this;
    }

    /**
     * Get conversionPrice
     *
     * @return float 
     */
    public function getConversionPrice()
    {
        return $this->conversionPrice;
    }

    /**
     * Set conversionCurrency
     *
     * @param string $conversionCurrency
     * @return BookingPrice
     */
    public function setConversionCurrency($conversionCurrency)
    {
        $this->conversionCurrency = $conversionCurrency;

        return $this;
    }

    /**
     * Get conversionCurrency
     *
     * @return string 
     */
    public function getConversionCurrency()
    {
        return $this->conversionCurrency;
    }

    /**
     * Set booking
     *
     * @param \Mytrip\AdminBundle\Entity\Booking $booking
     * @return BookingPrice
     */
    public function setBooking(\Mytrip\AdminBundle\Entity\Booking $booking = null)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return \Mytrip\AdminBundle\Entity\Booking 
     */
    public function getBooking()
    {
        return $this->booking;
    }
    /**
     * @var float
     */
    private $reservationPrice;

    /**
     * @var float
     */
    private $reservationCharge;

    /**
     * @var float
     */
    private $reservationTotalPrice;

    /**
     * @var string
     */
    private $paymenttype;


    /**
     * Set reservationPrice
     *
     * @param float $reservationPrice
     * @return BookingPrice
     */
    public function setReservationPrice($reservationPrice)
    {
        $this->reservationPrice = $reservationPrice;

        return $this;
    }

    /**
     * Get reservationPrice
     *
     * @return float 
     */
    public function getReservationPrice()
    {
        return $this->reservationPrice;
    }

    /**
     * Set reservationCharge
     *
     * @param float $reservationCharge
     * @return BookingPrice
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
     * Set reservationTotalPrice
     *
     * @param float $reservationTotalPrice
     * @return BookingPrice
     */
    public function setReservationTotalPrice($reservationTotalPrice)
    {
        $this->reservationTotalPrice = $reservationTotalPrice;

        return $this;
    }

    /**
     * Get reservationTotalPrice
     *
     * @return float 
     */
    public function getReservationTotalPrice()
    {
        return $this->reservationTotalPrice;
    }

    /**
     * Set paymenttype
     *
     * @param string $paymenttype
     * @return BookingPrice
     */
    public function setPaymenttype($paymenttype)
    {
        $this->paymenttype = $paymenttype;

        return $this;
    }

    /**
     * Get paymenttype
     *
     * @return string 
     */
    public function getPaymenttype()
    {
        return $this->paymenttype;
    }
}
