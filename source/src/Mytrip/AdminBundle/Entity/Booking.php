<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 */
class Booking
{
    /**
     * @var integer
     */
    private $bookingId;

    /**
     * @var \DateTime
     */
    private $fromDate;

    /**
     * @var \DateTime
     */
    private $toDate;

    /**
     * @var integer
     */
    private $noOfDays;

    /**
     * @var integer
     */
    private $guests;

    /**
     * @var integer
     */
    private $adults;

    /**
     * @var integer
     */
    private $child;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \Mytrip\AdminBundle\Entity\Hostal
     */
    private $hostal;

    private $rooms;

    /**
     * @var \Mytrip\AdminBundle\Entity\User
     */
    private $user;

    public function __construct() 
    {
        $this->rooms = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * Get bookingId
     *
     * @return integer 
     */
    public function getBookingId()
    {
        return $this->bookingId;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     * @return Booking
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return \DateTime 
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param \DateTime $toDate
     * @return Booking
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return \DateTime 
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set noOfDays
     *
     * @param integer $noOfDays
     * @return Booking
     */
    public function setNoOfDays($noOfDays)
    {
        $this->noOfDays = $noOfDays;

        return $this;
    }

    /**
     * Get noOfDays
     *
     * @return integer 
     */
    public function getNoOfDays()
    {
        return $this->noOfDays;
    }

    /**
     * Set guests
     *
     * @param integer $guests
     * @return Booking
     */
    public function setGuests($guests)
    {
        $this->guests = $guests;

        return $this;
    }

    /**
     * Get guests
     *
     * @return integer 
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * Set adults
     *
     * @param integer $adults
     * @return Booking
     */
    public function setAdults($adults)
    {
        $this->adults = $adults;

        return $this;
    }

    /**
     * Get adults
     *
     * @return integer 
     */
    public function getAdults()
    {
        return $this->adults;
    }

    /**
     * Set child
     *
     * @param integer $child
     * @return Booking
     */
    public function setChild($child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child
     *
     * @return integer 
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Booking
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
     * Set status
     *
     * @param string $status
     * @return Booking
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
     * Set hostal
     *
     * @param \Mytrip\AdminBundle\Entity\Hostal $hostal
     * @return Booking
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
     * Set user
     *
     * @param \Mytrip\AdminBundle\Entity\User $user
     * @return Booking
     */
    public function setUser(\Mytrip\AdminBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Mytrip\AdminBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var integer
     */
    private $noOfRooms;


    /**
     * Set noOfRooms
     *
     * @param integer $noOfRooms
     * @return Booking
     */
    public function setNoOfRooms($noOfRooms)
    {
        $this->noOfRooms = $noOfRooms;

        return $this;
    }

    /**
     * Get noOfRooms
     *
     * @return integer 
     */
    public function getNoOfRooms()
    {
        return $this->noOfRooms;
    }
    
    /**
     * Add rooms
     *
     * @param \Mytrip\AdminBundle\Entity\HostalRooms $rooms
     * @return Booking
     */
    public function addRoom(\Mytrip\AdminBundle\Entity\HostalRooms $rooms)
    {
        $this->rooms[] = $rooms;
        return $this;
    }

    /**
     * Remove rooms
     *
     * @param \Mytrip\AdminBundle\Entity\HostalRooms $rooms
     */
    public function removeRoom(\Mytrip\AdminBundle\Entity\HostalRooms $rooms)
    {
        $this->rooms->removeElement($rooms);
    }

    /**
     * Get rooms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRooms()
    {
        return $this->rooms;
    }
}
