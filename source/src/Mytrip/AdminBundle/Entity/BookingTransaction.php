<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingTransaction
 */
class BookingTransaction
{
    /**
     * @var integer
     */
    private $bookingTransactionId;

    /**
     * @var string
     */
    private $paymentType;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $transactionDate;

    /**
     * @var string
     */
    private $transactionAmount;

    /**
     * @var \Mytrip\AdminBundle\Entity\Booking
     */
    private $booking;


    /**
     * Get bookingTransactionId
     *
     * @return integer 
     */
    public function getBookingTransactionId()
    {
        return $this->bookingTransactionId;
    }

    /**
     * Set paymentType
     *
     * @param string $paymentType
     * @return BookingTransaction
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return string 
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     * @return BookingTransaction
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set transactionDate
     *
     * @param string $transactionDate
     * @return BookingTransaction
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * Get transactionDate
     *
     * @return string 
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Set transactionAmount
     *
     * @param string $transactionAmount
     * @return BookingTransaction
     */
    public function setTransactionAmount($transactionAmount)
    {
        $this->transactionAmount = $transactionAmount;

        return $this;
    }

    /**
     * Get transactionAmount
     *
     * @return string 
     */
    public function getTransactionAmount()
    {
        return $this->transactionAmount;
    }

    /**
     * Set booking
     *
     * @param \Mytrip\AdminBundle\Entity\Booking $booking
     * @return BookingTransaction
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
     * @var string
     */
    private $transactionCurrency;


    /**
     * Set transactionCurrency
     *
     * @param string $transactionCurrency
     * @return BookingTransaction
     */
    public function setTransactionCurrency($transactionCurrency)
    {
        $this->transactionCurrency = $transactionCurrency;

        return $this;
    }

    /**
     * Get transactionCurrency
     *
     * @return string 
     */
    public function getTransactionCurrency()
    {
        return $this->transactionCurrency;
    }
}
