<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 */
class Country
{
    /**
     * @var integer
     */
    private $cid;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $isocountry;


    /**
     * Get cid
     *
     * @return integer 
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Country
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
     * Set isocountry
     *
     * @param string $isocountry
     * @return Country
     */
    public function setIsocountry($isocountry)
    {
        $this->isocountry = $isocountry;

        return $this;
    }

    /**
     * Get isocountry
     *
     * @return string 
     */
    public function getIsocountry()
    {
        return $this->isocountry;
    }
}
