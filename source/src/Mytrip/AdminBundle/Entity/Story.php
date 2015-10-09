<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Story
 */
class Story
{
    /**
     * @var integer
     */
    private $storyId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \Mytrip\AdminBundle\Entity\Hostal
     */
    private $hostal;


    /**
     * Get storyId
     *
     * @return integer 
     */
    public function getStoryId()
    {
        return $this->storyId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Story
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
     * @return Story
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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Story
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
     * Set status
     *
     * @param string $status
     * @return Story
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
     * Set hostal
     *
     * @param \Mytrip\AdminBundle\Entity\Hostal $hostal
     * @return Story
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
     * @var \Mytrip\AdminBundle\Entity\Destination
     */
    private $destination;


    /**
     * Set destination
     *
     * @param \Mytrip\AdminBundle\Entity\Destination $destination
     * @return Story
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
    private $topStory;


    /**
     * Set topStory
     *
     * @param string $topStory
     * @return Story
     */
    public function setTopStory($topStory)
    {
        $this->topStory = $topStory;

        return $this;
    }

    /**
     * Get topStory
     *
     * @return string 
     */
    public function getTopStory()
    {
        return $this->topStory;
    }
}
