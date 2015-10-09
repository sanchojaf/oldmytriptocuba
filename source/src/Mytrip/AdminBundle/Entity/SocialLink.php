<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SocialLink
 */
class SocialLink
{
    /**
     * @var integer
     */
    private $socialLinkId;

    /**
     * @var string
     */
    private $site;

    /**
     * @var string
     */
    private $link;

    /**
     * @var \DateTime
     */
    private $createdDate;


    /**
     * Get socialLinkId
     *
     * @return integer 
     */
    public function getSocialLinkId()
    {
        return $this->socialLinkId;
    }

    /**
     * Set site
     *
     * @param string $site
     * @return SocialLink
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return SocialLink
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return SocialLink
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
}
