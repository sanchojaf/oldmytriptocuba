<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visits
 */
class Visits
{
    /**
     * @var integer
     */
    private $visitId;

    /**
     * @var string
     */
    private $visitType;

    /**
     * @var integer
     */
    private $typeId;

    /**
     * @var \DateTime
     */
    private $visitDate;

    /**
     * @var integer
     */
    private $count;


    /**
     * Get visitId
     *
     * @return integer 
     */
    public function getVisitId()
    {
        return $this->visitId;
    }

    /**
     * Set visitType
     *
     * @param string $visitType
     * @return Visits
     */
    public function setVisitType($visitType)
    {
        $this->visitType = $visitType;

        return $this;
    }

    /**
     * Get visitType
     *
     * @return string 
     */
    public function getVisitType()
    {
        return $this->visitType;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return Visits
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
     * Set visitDate
     *
     * @param \DateTime $visitDate
     * @return Visits
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime 
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Visits
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }
}
