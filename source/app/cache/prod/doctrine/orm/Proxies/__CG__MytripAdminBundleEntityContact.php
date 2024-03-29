<?php

namespace Proxies\__CG__\Mytrip\AdminBundle\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Contact extends \Mytrip\AdminBundle\Entity\Contact implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'contactId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'name', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'email', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'phone', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'subject', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'message', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'view', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'reply', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'replyMessage', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'replyDate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'createdDate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'lan', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'replysubject');
        }

        return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'contactId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'name', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'email', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'phone', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'subject', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'message', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'view', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'reply', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'replyMessage', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'replyDate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'createdDate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'lan', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\Contact' . "\0" . 'replysubject');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Contact $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getContactId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getContactId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContactId', array());

        return parent::getContactId();
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', array($name));

        return parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', array());

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', array($email));

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', array());

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setPhone($phone)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhone', array($phone));

        return parent::setPhone($phone);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhone()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhone', array());

        return parent::getPhone();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubject($subject)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubject', array($subject));

        return parent::setSubject($subject);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubject', array());

        return parent::getSubject();
    }

    /**
     * {@inheritDoc}
     */
    public function setMessage($message)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMessage', array($message));

        return parent::setMessage($message);
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMessage', array());

        return parent::getMessage();
    }

    /**
     * {@inheritDoc}
     */
    public function setView($view)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setView', array($view));

        return parent::setView($view);
    }

    /**
     * {@inheritDoc}
     */
    public function getView()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getView', array());

        return parent::getView();
    }

    /**
     * {@inheritDoc}
     */
    public function setReply($reply)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReply', array($reply));

        return parent::setReply($reply);
    }

    /**
     * {@inheritDoc}
     */
    public function getReply()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReply', array());

        return parent::getReply();
    }

    /**
     * {@inheritDoc}
     */
    public function setReplyMessage($replyMessage)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReplyMessage', array($replyMessage));

        return parent::setReplyMessage($replyMessage);
    }

    /**
     * {@inheritDoc}
     */
    public function getReplyMessage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReplyMessage', array());

        return parent::getReplyMessage();
    }

    /**
     * {@inheritDoc}
     */
    public function setReplyDate($replyDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReplyDate', array($replyDate));

        return parent::setReplyDate($replyDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getReplyDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReplyDate', array());

        return parent::getReplyDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedDate($createdDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreatedDate', array($createdDate));

        return parent::setCreatedDate($createdDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedDate', array());

        return parent::getCreatedDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setLan($lan)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLan', array($lan));

        return parent::setLan($lan);
    }

    /**
     * {@inheritDoc}
     */
    public function getLan()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLan', array());

        return parent::getLan();
    }

    /**
     * {@inheritDoc}
     */
    public function setReplysubject($replysubject)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReplysubject', array($replysubject));

        return parent::setReplysubject($replysubject);
    }

    /**
     * {@inheritDoc}
     */
    public function getReplysubject()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReplysubject', array());

        return parent::getReplysubject();
    }

}
