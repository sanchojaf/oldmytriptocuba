<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Destination
 */
class Destination
{
    /**
     * @var integer
     */
    private $destinationId;

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
     * @var \Mytrip\AdminBundle\Entity\Country
     */
    private $country;

    /**
     * @var \Mytrip\AdminBundle\Entity\States
     */
    private $province;


    /**
     * Get destinationId
     *
     * @return integer 
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Destination
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
     * @return Destination
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
     * Set video
     *
     * @param string $video
     * @return Destination
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
     * @return Destination
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
     * @return Destination
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
     * @return Destination
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
     * @return Destination
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
     * @return Destination
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
     * Set country
     *
     * @param \Mytrip\AdminBundle\Entity\Country $country
     * @return Destination
     */
    public function setCountry(\Mytrip\AdminBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Mytrip\AdminBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set province
     *
     * @param \Mytrip\AdminBundle\Entity\States $province
     * @return Destination
     */
    public function setProvince(\Mytrip\AdminBundle\Entity\States $province = null)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return \Mytrip\AdminBundle\Entity\States 
     */
    public function getProvince()
    {
        return $this->province;
    }
    /**
     * @var string
     */
    private $tripadvisor;


    /**
     * Set tripadvisor
     *
     * @param string $tripadvisor
     * @return Destination
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
}
