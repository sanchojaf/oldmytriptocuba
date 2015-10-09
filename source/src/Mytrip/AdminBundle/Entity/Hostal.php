<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hostal
 */
class Hostal
{
    /**
     * @var integer
     */
    private $hostalId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

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
    private $video;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var \DateTime
     */
    private $modifyDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \Mytrip\AdminBundle\Entity\Destination
     */
    private $destination;


    /**
     * Get hostalId
     *
     * @return integer 
     */
    public function getHostalId()
    {
        return $this->hostalId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Hostal
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Hostal
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Hostal
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
     * @return Hostal
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
     * Set video
     *
     * @param string $video
     * @return Hostal
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Hostal
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Hostal
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Hostal
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
     * Set modifyDate
     *
     * @param \DateTime $modifyDate
     * @return Hostal
     */
    public function setModifyDate($modifyDate)
    {
        $this->modifyDate = $modifyDate;

        return $this;
    }

    /**
     * Get modifyDate
     *
     * @return \DateTime 
     */
    public function getModifyDate()
    {
        return $this->modifyDate;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Hostal
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
     * Set destination
     *
     * @param \Mytrip\AdminBundle\Entity\Destination $destination
     * @return Hostal
     */
    public function setDestination(\Mytrip\AdminBundle\Entity\Destination $destination = null)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return \Mytrip\AdminBundle\Entity\Destination 
     */
    public function getDestination()
    {
        return $this->destination;
    }
    /**
     * @var string
     */
    private $tripadvisor;


    /**
     * Set tripadvisor
     *
     * @param string $tripadvisor
     * @return Hostal
     */
    public function setTripadvisor($tripadvisor)
    {
        $this->tripadvisor = $tripadvisor;

        return $this;
    }

    /**
     * Get tripadvisor
     *
     * @return string 
     */
    public function getTripadvisor()
    {
        return $this->tripadvisor;
    }
    /**
     * @var string
     */
    private $ownerEmail;


    /**
     * Set ownerEmail
     *
     * @param string $ownerEmail
     * @return Hostal
     */
    public function setOwnerEmail($ownerEmail)
    {
        $this->ownerEmail = $ownerEmail;

        return $this;
    }

    /**
     * Get ownerEmail
     *
     * @return string 
     */
    public function getOwnerEmail()
    {
        return $this->ownerEmail;
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
     * @return Hostal
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
     * @return Hostal
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
