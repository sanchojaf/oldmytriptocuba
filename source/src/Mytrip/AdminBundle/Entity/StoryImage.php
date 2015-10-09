<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoryImage
 */
class StoryImage
{
    /**
     * @var integer
     */
    private $storyImageId;

    /**
     * @var string
     */
    private $image;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var \Mytrip\AdminBundle\Entity\Story
     */
    private $story;


    /**
     * Get storyImageId
     *
     * @return integer 
     */
    public function getStoryImageId()
    {
        return $this->storyImageId;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return StoryImage
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
     * @return StoryImage
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
     * Set story
     *
     * @param \Mytrip\AdminBundle\Entity\Story $story
     * @return StoryImage
     */
    public function setStory(\Mytrip\AdminBundle\Entity\Story $story = null)
    {
        $this->story = $story;

        return $this;
    }

    /**
     * Get story
     *
     * @return \Mytrip\AdminBundle\Entity\Story 
     */
    public function getStory()
    {
        return $this->story;
    }
}
