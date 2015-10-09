<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banner
 */
class Banner
{
    /**
     * @var integer
     */
    private $bannerId;

    /**
     * @var string
     */
    private $bannerType;

    /**
     * @var string
     */
    private $image;

    /**
     * @var integer
     */
    private $typeId;

    /**
     * @var string
     */
    private $status;


    /**
     * Get bannerId
     *
     * @return integer 
     */
    public function getBannerId()
    {
        return $this->bannerId;
    }

    /**
     * Set bannerType
     *
     * @param string $bannerType
     * @return Banner
     */
    public function setBannerType($bannerType)
    {
        $this->bannerType = $bannerType;

        return $this;
    }

    /**
     * Get bannerType
     *
     * @return string 
     */
    public function getBannerType()
    {
        return $this->bannerType;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Banner
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return Banner
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Banner
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
}
