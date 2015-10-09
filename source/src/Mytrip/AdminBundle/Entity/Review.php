<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Review
 */
class Review
{
    /**
     * @var integer
     */
    private $reviewId;

    /**
     * @var string
     */
    private $reviewType;

    /**
     * @var integer
     */
    private $typeId;

    /**
     * @var integer
     */
    private $rating;

    /**
     * @var string
     */
    private $review;

    /**
     * @var string
     */
    private $lan;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \Mytrip\AdminBundle\Entity\User
     */
    private $user;


    /**
     * Get reviewId
     *
     * @return integer 
     */
    public function getReviewId()
    {
        return $this->reviewId;
    }

    /**
     * Set reviewType
     *
     * @param string $reviewType
     * @return Review
     */
    public function setReviewType($reviewType)
    {
        $this->reviewType = $reviewType;

        return $this;
    }

    /**
     * Get reviewType
     *
     * @return string 
     */
    public function getReviewType()
    {
        return $this->reviewType;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return Review
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
     * Set rating
     *
     * @param integer $rating
     * @return Review
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set review
     *
     * @param string $review
     * @return Review
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return string 
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set lan
     *
     * @param string $lan
     * @return Review
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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Review
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
     * @return Review
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
     * Set user
     *
     * @param \Mytrip\AdminBundle\Entity\User $user
     * @return Review
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
