<?php

namespace Proxies\__CG__\Mytrip\AdminBundle\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class HostalRooms extends \Mytrip\AdminBundle\Entity\HostalRooms implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'roomId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'rooms', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'guests', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'adults', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'child', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'price', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'hostal', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'roomtype');
        }

        return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'roomId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'rooms', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'guests', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'adults', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'child', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'price', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'hostal', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\HostalRooms' . "\0" . 'roomtype');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (HostalRooms $proxy) {
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
    public function getRoomId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getRoomId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoomId', array());

        return parent::getRoomId();
    }

    /**
     * {@inheritDoc}
     */
    public function setRooms($rooms)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRooms', array($rooms));

        return parent::setRooms($rooms);
    }

    /**
     * {@inheritDoc}
     */
    public function getRooms()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRooms', array());

        return parent::getRooms();
    }

    /**
     * {@inheritDoc}
     */
    public function setGuests($guests)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setGuests', array($guests));

        return parent::setGuests($guests);
    }

    /**
     * {@inheritDoc}
     */
    public function getGuests()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGuests', array());

        return parent::getGuests();
    }

    /**
     * {@inheritDoc}
     */
    public function setAdults($adults)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAdults', array($adults));

        return parent::setAdults($adults);
    }

    /**
     * {@inheritDoc}
     */
    public function getAdults()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAdults', array());

        return parent::getAdults();
    }

    /**
     * {@inheritDoc}
     */
    public function setChild($child)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setChild', array($child));

        return parent::setChild($child);
    }

    /**
     * {@inheritDoc}
     */
    public function getChild()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getChild', array());

        return parent::getChild();
    }

    /**
     * {@inheritDoc}
     */
    public function setPrice($price)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPrice', array($price));

        return parent::setPrice($price);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrice()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPrice', array());

        return parent::getPrice();
    }

    /**
     * {@inheritDoc}
     */
    public function setHostal(\Mytrip\AdminBundle\Entity\Hostal $hostal = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHostal', array($hostal));

        return parent::setHostal($hostal);
    }

    /**
     * {@inheritDoc}
     */
    public function getHostal()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHostal', array());

        return parent::getHostal();
    }

    /**
     * {@inheritDoc}
     */
    public function setRoomtype($roomtype)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRoomtype', array($roomtype));

        return parent::setRoomtype($roomtype);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoomtype()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoomtype', array());

        return parent::getRoomtype();
    }

}
