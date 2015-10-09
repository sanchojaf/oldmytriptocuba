<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmailContent
 */
class EmailContent
{
    /**
     * @var integer
     */
    private $emailContentId;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $emailcontent;

    /**
     * @var string
     */
    private $lan;

    /**
     * @var \Mytrip\AdminBundle\Entity\EmailList
     */
    private $emailList;


    /**
     * Get emailContentId
     *
     * @return integer 
     */
    public function getEmailContentId()
    {
        return $this->emailContentId;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return EmailContent
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set emailcontent
     *
     * @param string $emailcontent
     * @return EmailContent
     */
    public function setEmailcontent($emailcontent)
    {
        $this->emailcontent = $emailcontent;

        return $this;
    }

    /**
     * Get emailcontent
     *
     * @return string 
     */
    public function getEmailcontent()
    {
        return $this->emailcontent;
    }

    /**
     * Set lan
     *
     * @param string $lan
     * @return EmailContent
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
     * Set emailList
     *
     * @param \Mytrip\AdminBundle\Entity\EmailList $emailList
     * @return EmailContent
     */
    public function setEmailList(\Mytrip\AdminBundle\Entity\EmailList $emailList = null)
    {
        $this->emailList = $emailList;

        return $this;
    }

    /**
     * Get emailList
     *
     * @return \Mytrip\AdminBundle\Entity\EmailList 
     */
    public function getEmailList()
    {
        return $this->emailList;
    }
}
