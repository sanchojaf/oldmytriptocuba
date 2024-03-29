<?php

namespace Proxies\__CG__\Mytrip\AdminBundle\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class User extends \Mytrip\AdminBundle\Entity\User implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'userId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'firstname', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'lastname', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'username', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'password', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'email', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'phone', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'mobile', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'address', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'address2', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'city', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'province', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'country', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'lan', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'status', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'createdDate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'modifyDate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'key', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'userKey', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'image', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'gender', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'dob', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'zip', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'cccode', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'cmcode');
        }

        return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'userId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'firstname', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'lastname', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'username', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'password', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'email', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'phone', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'mobile', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'address', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'address2', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'city', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'province', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'country', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'lan', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'status', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'createdDate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'modifyDate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'key', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'userKey', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'image', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'gender', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'dob', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'zip', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'cccode', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\User' . "\0" . 'cmcode');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (User $proxy) {
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
    public function getUserId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getUserId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUserId', array());

        return parent::getUserId();
    }

    /**
     * {@inheritDoc}
     */
    public function setFirstname($firstname)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFirstname', array($firstname));

        return parent::setFirstname($firstname);
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstname()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFirstname', array());

        return parent::getFirstname();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastname($lastname)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastname', array($lastname));

        return parent::setLastname($lastname);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastname()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastname', array());

        return parent::getLastname();
    }

    /**
     * {@inheritDoc}
     */
    public function setUsername($username)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUsername', array($username));

        return parent::setUsername($username);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsername', array());

        return parent::getUsername();
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword($password)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPassword', array($password));

        return parent::setPassword($password);
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPassword', array());

        return parent::getPassword();
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
    public function setMobile($mobile)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMobile', array($mobile));

        return parent::setMobile($mobile);
    }

    /**
     * {@inheritDoc}
     */
    public function getMobile()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMobile', array());

        return parent::getMobile();
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress($address)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAddress', array($address));

        return parent::setAddress($address);
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAddress', array());

        return parent::getAddress();
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress2($address2)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAddress2', array($address2));

        return parent::setAddress2($address2);
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress2()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAddress2', array());

        return parent::getAddress2();
    }

    /**
     * {@inheritDoc}
     */
    public function setCity($city)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCity', array($city));

        return parent::setCity($city);
    }

    /**
     * {@inheritDoc}
     */
    public function getCity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCity', array());

        return parent::getCity();
    }

    /**
     * {@inheritDoc}
     */
    public function setProvince($province)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setProvince', array($province));

        return parent::setProvince($province);
    }

    /**
     * {@inheritDoc}
     */
    public function getProvince()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProvince', array());

        return parent::getProvince();
    }

    /**
     * {@inheritDoc}
     */
    public function setCountry($country)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCountry', array($country));

        return parent::setCountry($country);
    }

    /**
     * {@inheritDoc}
     */
    public function getCountry()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCountry', array());

        return parent::getCountry();
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
    public function setStatus($status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', array($status));

        return parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', array());

        return parent::getStatus();
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
    public function setModifyDate($modifyDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setModifyDate', array($modifyDate));

        return parent::setModifyDate($modifyDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getModifyDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getModifyDate', array());

        return parent::getModifyDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setKey($key)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setKey', array($key));

        return parent::setKey($key);
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKey', array());

        return parent::getKey();
    }

    /**
     * {@inheritDoc}
     */
    public function setUserKey($userKey)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUserKey', array($userKey));

        return parent::setUserKey($userKey);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserKey()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUserKey', array());

        return parent::getUserKey();
    }

    /**
     * {@inheritDoc}
     */
    public function setImage($image)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setImage', array($image));

        return parent::setImage($image);
    }

    /**
     * {@inheritDoc}
     */
    public function getImage()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getImage', array());

        return parent::getImage();
    }

    /**
     * {@inheritDoc}
     */
    public function setGender($gender)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setGender', array($gender));

        return parent::setGender($gender);
    }

    /**
     * {@inheritDoc}
     */
    public function getGender()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGender', array());

        return parent::getGender();
    }

    /**
     * {@inheritDoc}
     */
    public function setDob($dob)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDob', array($dob));

        return parent::setDob($dob);
    }

    /**
     * {@inheritDoc}
     */
    public function getDob()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDob', array());

        return parent::getDob();
    }

    /**
     * {@inheritDoc}
     */
    public function setZip($zip)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setZip', array($zip));

        return parent::setZip($zip);
    }

    /**
     * {@inheritDoc}
     */
    public function getZip()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getZip', array());

        return parent::getZip();
    }

    /**
     * {@inheritDoc}
     */
    public function setCccode($cccode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCccode', array($cccode));

        return parent::setCccode($cccode);
    }

    /**
     * {@inheritDoc}
     */
    public function getCccode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCccode', array());

        return parent::getCccode();
    }

    /**
     * {@inheritDoc}
     */
    public function setCmcode($cmcode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCmcode', array($cmcode));

        return parent::setCmcode($cmcode);
    }

    /**
     * {@inheritDoc}
     */
    public function getCmcode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCmcode', array());

        return parent::getCmcode();
    }

}
