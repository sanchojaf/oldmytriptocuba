<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingCancel
 */
class BookingCancel
{
    /**
     * @var integer
     */
    private $bookingCancelId;

    /**
     * @var \DateTime
     */
    private $cancelDate;

    /**
     * @var string
     */
    private $cancelReason;

    /**
     * @var float
     */
    private $refundAmount;

    /**
     * @var string
     */
    private $refundReferenceno;

    /**
     * @var \DateTime
     */
    private $refundDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var \Mytrip\AdminBundle\Entity\Booking
     */
    private $booking;


    /**
     * Get bookingCancelId
     *
     * @return integer 
     */
    public function getBookingCancelId()
    {
        return $this->bookingCancelId;
    }

    /**
     * Set cancelDate
     *
     * @param \DateTime $cancelDate
     * @return BookingCancel
     */
    public function setCancelDate($cancelDate)
    {
        $this->cancelDate = $cancelDate;

        return $this;
    }

    /**
     * Get cancelDate
     *
     * @return \DateTime 
     */
    public function getCancelDate()
    {
        return $this->cancelDate;
    }

    /**
     * Set cancelReason
     *
     * @param string $cancelReason
     * @return BookingCancel
     */
    public function setCancelReason($cancelReason)
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }

    /**
     * Get cancelReason
     *
     * @return string 
     */
    public function getCancelReason()
    {
        return $this->cancelReason;
    }

    /**
     * Set refundAmount
     *
     * @param float $refundAmount
     * @return BookingCancel
     */
    public function setRefundAmount($refundAmount)
    {
        $this->refundAmount = $refundAmount;

        return $this;
    }

    /**
     * Get refundAmount
     *
     * @return float 
     */
    public function getRefundAmount()
    {
        return $this->refundAmount;
    }

    /**
     * Set refundReferenceno
     *
     * @param string $refundReferenceno
     * @return BookingCancel
     */
    public function setRefundReferenceno($refundReferenceno)
    {
        $this->refundReferenceno = $refundReferenceno;

        return $this;
    }

    /**
     * Get refundReferenceno
     *
     * @return string 
     */
    public function getRefundReferenceno()
    {
        return $this->refundReferenceno;
    }

    /**
     * Set refundDate
     *
     * @param \DateTime $refundDate
     * @return BookingCancel
     */
    public function setRefundDate($refundDate)
    {
        $this->refundDate = $refundDate;

        return $this;
    }

    /**
     * Get refundDate
     *
     * @return \DateTime 
     */
    public function getRefundDate()
    {
        return $this->refundDate;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return BookingCancel
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return BookingCancel
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
     * Set booking
     *
     * @param \Mytrip\AdminBundle\Entity\Booking $booking
     * @return BookingCancel
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
     * @var integer
     */
    private $cancelPercentage;


    /**
     * Set cancelPercentage
     *
     * @param integer $cancelPercentage
     * @return BookingCancel
     */
    public function setCancelPercentage($cancelPercentage)
    {
        $this->cancelPercentage = $cancelPercentage;

        return $this;
    }

    /**
     * Get cancelPercentage
     *
     * @return integer 
     */
    public function getCancelPercentage()
    {
        return $this->cancelPercentage;
    }
    /**
     * @var string
     */
    private $refundCurrency;


    /**
     * Set refundCurrency
     *
     * @param string $refundCurrency
     * @return BookingCancel
     */
    public function setRefundCurrency($refundCurrency)
    {
        $this->refundCurrency = $refundCurrency;

        return $this;
    }

    /**
     * Get refundCurrency
     *
     * @return string 
     */
    public function getRefundCurrency()
    {
        return $this->refundCurrency;
    }
}
