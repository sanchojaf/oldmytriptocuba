<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingInfo
 */
class BookingInfo
{
    /**
     * @var integer
     */
    private $bookingInfoId;

    /**
     * @var integer
     */
    private $bookingId;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $mobile;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $address1;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $province;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var string
     */
    private $city;


    /**
     * Get bookingInfoId
     *
     * @return integer 
     */
    public function getBookingInfoId()
    {
        return $this->bookingInfoId;
    }

    /**
     * Set bookingId
     *
     * @param integer $bookingId
     * @return BookingInfo
     */
    public function setBookingId($bookingId)
    {
        $this->bookingId = $bookingId;

        return $this;
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
     * Set firstname
     *
     * @param string $firstname
     * @return BookingInfo
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return BookingInfo
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return BookingInfo
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return BookingInfo
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return BookingInfo
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return BookingInfo
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return BookingInfo
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address1
     *
     * @param string $address1
     * @return BookingInfo
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return BookingInfo
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set province
     *
     * @param string $province
     * @return BookingInfo
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return string 
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return BookingInfo
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return BookingInfo
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }
    /**
     * @var \Mytrip\AdminBundle\Entity\Booking
     */
    private $booking;


    /**
     * Set booking
     *
     * @param \Mytrip\AdminBundle\Entity\Booking $booking
     * @return BookingInfo
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
    private $cccode;

    /**
     * @var integer
     */
    private $cmcode;


    /**
     * Set cccode
     *
     * @param integer $cccode
     * @return BookingInfo
     */
    public function setCccode($cccode)
    {
        $this->cccode = $cccode;

        return $this;
    }

    /**
     * Get cccode
     *
     * @return integer 
     */
    public function getCccode()
    {
        return $this->cccode;
    }

    /**
     * Set cmcode
     *
     * @param integer $cmcode
     * @return BookingInfo
     */
    public function setCmcode($cmcode)
    {
        $this->cmcode = $cmcode;

        return $this;
    }

    /**
     * Get cmcode
     *
     * @return integer 
     */
    public function getCmcode()
    {
        return $this->cmcode;
    }
}
