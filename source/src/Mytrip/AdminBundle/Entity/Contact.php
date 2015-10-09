<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 */
class Contact
{
    /**
     * @var integer
     */
    private $contactId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $view;

    /**
     * @var string
     */
    private $reply;

    /**
     * @var string
     */
    private $replyMessage;

    /**
     * @var \DateTime
     */
    private $replyDate;

    /**
     * @var \DateTime
     */
    private $createdDate;

    /**
     * @var string
     */
    private $lan;


    /**
     * Get contactId
     *
     * @return integer 
     */
    public function getContactId()
    {
        return $this->contactId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Contact
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
     * Set email
     *
     * @param string $email
     * @return Contact
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
     * Set phone
     *
     * @param string $phone
     * @return Contact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Contact
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
     * Set message
     *
     * @param string $message
     * @return Contact
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set view
     *
     * @param string $view
     * @return Contact
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view
     *
     * @return string 
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set reply
     *
     * @param string $reply
     * @return Contact
     */
    public function setReply($reply)
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * Get reply
     *
     * @return string 
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * Set replyMessage
     *
     * @param string $replyMessage
     * @return Contact
     */
    public function setReplyMessage($replyMessage)
    {
        $this->replyMessage = $replyMessage;

        return $this;
    }

    /**
     * Get replyMessage
     *
     * @return string 
     */
    public function getReplyMessage()
    {
        return $this->replyMessage;
    }

    /**
     * Set replyDate
     *
     * @param \DateTime $replyDate
     * @return Contact
     */
    public function setReplyDate($replyDate)
    {
        $this->replyDate = $replyDate;

        return $this;
    }

    /**
     * Get replyDate
     *
     * @return \DateTime 
     */
    public function getReplyDate()
    {
        return $this->replyDate;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Contact
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
     * Set lan
     *
     * @param string $lan
     * @return Contact
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
     * @var string
     */
    private $replysubject;


    /**
     * Set replysubject
     *
     * @param string $replysubject
     * @return Contact
     */
    public function setReplysubject($replysubject)
    {
        $this->replysubject = $replysubject;

        return $this;
    }

    /**
     * Get replysubject
     *
     * @return string 
     */
    public function getReplysubject()
    {
        return $this->replysubject;
    }
}
