<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmailList
 */
class EmailList
{
    /**
     * @var integer
     */
    private $emailListId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $fromname;

    /**
     * @var string
     */
    private $fromemail;

    /**
     * @var string
     */
    private $tomail;

    /**
     * @var string
     */
    private $ccmail;


    /**
     * Get emailListId
     *
     * @return integer 
     */
    public function getEmailListId()
    {
        return $this->emailListId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return EmailList
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return EmailList
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set fromname
     *
     * @param string $fromname
     * @return EmailList
     */
    public function setFromname($fromname)
    {
        $this->fromname = $fromname;

        return $this;
    }

    /**
     * Get fromname
     *
     * @return string 
     */
    public function getFromname()
    {
        return $this->fromname;
    }

    /**
     * Set fromemail
     *
     * @param string $fromemail
     * @return EmailList
     */
    public function setFromemail($fromemail)
    {
        $this->fromemail = $fromemail;

        return $this;
    }

    /**
     * Get fromemail
     *
     * @return string 
     */
    public function getFromemail()
    {
        return $this->fromemail;
    }

    /**
     * Set tomail
     *
     * @param string $tomail
     * @return EmailList
     */
    public function setTomail($tomail)
    {
        $this->tomail = $tomail;

        return $this;
    }

    /**
     * Get tomail
     *
     * @return string 
     */
    public function getTomail()
    {
        return $this->tomail;
    }

    /**
     * Set ccmail
     *
     * @param string $ccmail
     * @return EmailList
     */
    public function setCcmail($ccmail)
    {
        $this->ccmail = $ccmail;

        return $this;
    }

    /**
     * Get ccmail
     *
     * @return string 
     */
    public function getCcmail()
    {
        return $this->ccmail;
    }
}
