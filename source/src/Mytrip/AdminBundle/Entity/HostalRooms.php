<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HostalRooms
 */
class HostalRooms
{
    /**
     * @var integer
     */
    private $roomId;

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
     * @var float
     */
    private $price;

    /**
     * @var \Mytrip\AdminBundle\Entity\Hostal
     */
    private $hostal;


    /**
     * Get roomId
     *
     * @return integer 
     */
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * Set guests
     *
     * @param integer $guests
     * @return HostalRooms
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
     * @return HostalRooms
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
     * @return HostalRooms
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
     * Set price
     *
     * @param float $price
     * @return HostalRooms
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set hostal
     *
     * @param \Mytrip\AdminBundle\Entity\Hostal $hostal
     * @return HostalRooms
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
     * @var string
     */
    private $roomtype;


    /**
     * Set roomtype
     *
     * @param string $roomtype
     * @return HostalRooms
     */
    public function setRoomtype($roomtype)
    {
        $this->roomtype = $roomtype;

        return $this;
    }

    /**
     * Get roomtype
     *
     * @return string 
     */
    public function getRoomtype()
    {
        return $this->roomtype;
    }
}
