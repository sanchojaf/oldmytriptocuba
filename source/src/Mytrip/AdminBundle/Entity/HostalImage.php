<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HostalImage
 */
class HostalImage
{
    /**
     * @var integer
     */
    private $hostalImageId;

    /**
     * @var string
     */
    private $image;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var \Mytrip\AdminBundle\Entity\Hostal
     */
    private $hostal;


    /**
     * Get hostalImageId
     *
     * @return integer 
     */
    public function getHostalImageId()
    {
        return $this->hostalImageId;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return HostalImage
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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return HostalImage
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
     * Set hostal
     *
     * @param \Mytrip\AdminBundle\Entity\Hostal $hostal
     * @return HostalImage
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
