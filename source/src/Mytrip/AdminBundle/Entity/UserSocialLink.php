<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSocialLink
 */
class UserSocialLink
{
    /**
     * @var integer
     */
    private $userSocialLinkId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $socialLink;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $image;

    /**
     * @var \DateTime
     */
    private $createdDate;


    /**
     * Get userSocialLinkId
     *
     * @return integer 
     */
    public function getUserSocialLinkId()
    {
        return $this->userSocialLinkId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserSocialLink
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set socialLink
     *
     * @param string $socialLink
     * @return UserSocialLink
     */
    public function setSocialLink($socialLink)
    {
        $this->socialLink = $socialLink;

        return $this;
    }

    /**
     * Get socialLink
     *
     * @return string 
     */
    public function getSocialLink()
    {
        return $this->socialLink;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return UserSocialLink
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set image
     *
     * @param integer $image
     * @return UserSocialLink
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return integer 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return UserSocialLink
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
     * @var string
     */
    private $email;


    /**
     * Set email
     *
     * @param string $email
     * @return UserSocialLink
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @var \Mytrip\AdminBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \Mytrip\AdminBundle\Entity\User $user
     * @return UserSocialLink
     */
    public function setUser(\Mytrip\AdminBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Mytrip\AdminBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
