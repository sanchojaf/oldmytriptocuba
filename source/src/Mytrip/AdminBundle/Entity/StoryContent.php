<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoryContent
 */
class StoryContent
{
    /**
     * @var integer
     */
    private $storyContentId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $subHead;

    /**
     * @var string
     */
    private $content;

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
    private $lan;

    /**
     * @var \Mytrip\AdminBundle\Entity\Story
     */
    private $story;


    /**
     * Get storyContentId
     *
     * @return integer 
     */
    public function getStoryContentId()
    {
        return $this->storyContentId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return StoryContent
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
     * Set subHead
     *
     * @param string $subHead
     * @return StoryContent
     */
    public function setSubHead($subHead)
    {
        $this->subHead = $subHead;

        return $this;
    }

    /**
     * Get subHead
     *
     * @return string 
     */
    public function getSubHead()
    {
        return $this->subHead;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return StoryContent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return StoryContent
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
     * @return StoryContent
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
     * @return StoryContent
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
     * Set lan
     *
     * @param string $lan
     * @return StoryContent
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
     * Set story
     *
     * @param \Mytrip\AdminBundle\Entity\Story $story
     * @return StoryContent
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
