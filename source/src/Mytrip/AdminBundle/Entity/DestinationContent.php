<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DestinationContent
 */
class DestinationContent
{
    /**
     * @var integer
     */
    private $destinationContentId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

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
     * @var \Mytrip\AdminBundle\Entity\Destination
     */
    private $destination;


    /**
     * Get destinationContentId
     *
     * @return integer 
     */
    public function getDestinationContentId()
    {
        return $this->destinationContentId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return DestinationContent
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
     * Set description
     *
     * @param string $description
     * @return DestinationContent
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
     * Set locationDesc
     *
     * @param string $locationDesc
     * @return DestinationContent
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
     * @return DestinationContent
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
     * @return DestinationContent
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
     * @return DestinationContent
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
     * @return DestinationContent
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
     * @return DestinationContent
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
     * @return DestinationContent
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
     * @return DestinationContent
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
     * @return DestinationContent
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
     * Set destination
     *
     * @param \Mytrip\AdminBundle\Entity\Destination $destination
     * @return DestinationContent
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
}
