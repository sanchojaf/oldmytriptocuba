<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HostalContent
 */
class HostalContent
{
    /**
     * @var integer
     */
    private $hostalContentId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $ownerName;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $smallDesc;

    /**
     * @var string
     */
    private $locationDesc;

    /**
     * @var string
     */
    private $metaTitle;

    /**
     * @var string
     */
    private $metaDescription;

    /**
     * @var string
     */
    private $metaKeyword;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $province;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $lan;

    /**
     * @var \Mytrip\AdminBundle\Entity\Hostal
     */
    private $hostal;


    /**
     * Get hostalContentId
     *
     * @return integer 
     */
    public function getHostalContentId()
    {
        return $this->hostalContentId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return HostalContent
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
     * Set ownerName
     *
     * @param string $ownerName
     * @return HostalContent
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    /**
     * Get ownerName
     *
     * @return string 
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return HostalContent
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set smallDesc
     *
     * @param string $smallDesc
     * @return HostalContent
     */
    public function setSmallDesc($smallDesc)
    {
        $this->smallDesc = $smallDesc;

        return $this;
    }

    /**
     * Get smallDesc
     *
     * @return string 
     */
    public function getSmallDesc()
    {
        return $this->smallDesc;
    }

    /**
     * Set locationDesc
     *
     * @param string $locationDesc
     * @return HostalContent
     */
    public function setLocationDesc($locationDesc)
    {
        $this->locationDesc = $locationDesc;

        return $this;
    }

    /**
     * Get locationDesc
     *
     * @return string 
     */
    public function getLocationDesc()
    {
        return $this->locationDesc;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return HostalContent
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return HostalContent
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeyword
     *
     * @param string $metaKeyword
     * @return HostalContent
     */
    public function setMetaKeyword($metaKeyword)
    {
        $this->metaKeyword = $metaKeyword;

        return $this;
    }

    /**
     * Get metaKeyword
     *
     * @return string 
     */
    public function getMetaKeyword()
    {
        return $this->metaKeyword;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return HostalContent
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
     * Set city
     *
     * @param string $city
     * @return HostalContent
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
     * Set province
     *
     * @param string $province
     * @return HostalContent
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
     * Set country
     *
     * @param string $country
     * @return HostalContent
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
     * Set lan
     *
     * @param string $lan
     * @return HostalContent
     */
    public function setLan($lan)
    {
        $this->lan = $lan;

        return $this;
    }

    /**
     * Get lan
     *
     * @return string 
     */
    public function getLan()
    {
        return $this->lan;
    }

    /**
     * Set hostal
     *
     * @param \Mytrip\AdminBundle\Entity\Hostal $hostal
     * @return HostalContent
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
}
