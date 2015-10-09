<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DestinationImage
 */
class DestinationImage
{
    /**
     * @var integer
     */
    private $destinationImageId;

    /**
     * @var string
     */
    private $image;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var \Mytrip\AdminBundle\Entity\Destination
     */
    private $destination;


    /**
     * Get destinationImageId
     *
     * @return integer 
     */
    public function getDestinationImageId()
    {
        return $this->destinationImageId;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return DestinationImage
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
     * @return DestinationImage
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
     * Set destination
     *
     * @param \Mytrip\AdminBundle\Entity\Destination $destination
     * @return DestinationImage
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
