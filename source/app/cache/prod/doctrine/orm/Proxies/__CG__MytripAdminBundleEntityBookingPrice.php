<?php

namespace Proxies\__CG__\Mytrip\AdminBundle\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class BookingPrice extends \Mytrip\AdminBundle\Entity\BookingPrice implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'bookingPriceId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'roomPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'totalPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'defaultCurrency', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'conversionRate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'conversionPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'conversionCurrency', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'booking', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'reservationPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'reservationCharge', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'reservationTotalPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'paymenttype');
        }

        return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'bookingPriceId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'roomPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'totalPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'defaultCurrency', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'conversionRate', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'conversionPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'conversionCurrency', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'booking', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'reservationPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'reservationCharge', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'reservationTotalPrice', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\BookingPrice' . "\0" . 'paymenttype');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (BookingPrice $proxy) {
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
    public function getBookingPriceId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getBookingPriceId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBookingPriceId', array());

        return parent::getBookingPriceId();
    }

    /**
     * {@inheritDoc}
     */
    public function setRoomPrice($roomPrice)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRoomPrice', array($roomPrice));

        return parent::setRoomPrice($roomPrice);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoomPrice()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoomPrice', array());

        return parent::getRoomPrice();
    }

    /**
     * {@inheritDoc}
     */
    public function setTotalPrice($totalPrice)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTotalPrice', array($totalPrice));

        return parent::setTotalPrice($totalPrice);
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalPrice()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTotalPrice', array());

        return parent::getTotalPrice();
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultCurrency($defaultCurrency)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDefaultCurrency', array($defaultCurrency));

        return parent::setDefaultCurrency($defaultCurrency);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultCurrency()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDefaultCurrency', array());

        return parent::getDefaultCurrency();
    }

    /**
     * {@inheritDoc}
     */
    public function setConversionRate($conversionRate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setConversionRate', array($conversionRate));

        return parent::setConversionRate($conversionRate);
    }

    /**
     * {@inheritDoc}
     */
    public function getConversionRate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getConversionRate', array());

        return parent::getConversionRate();
    }

    /**
     * {@inheritDoc}
     */
    public function setConversionPrice($conversionPrice)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setConversionPrice', array($conversionPrice));

        return parent::setConversionPrice($conversionPrice);
    }

    /**
     * {@inheritDoc}
     */
    public function getConversionPrice()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getConversionPrice', array());

        return parent::getConversionPrice();
    }

    /**
     * {@inheritDoc}
     */
    public function setConversionCurrency($conversionCurrency)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setConversionCurrency', array($conversionCurrency));

        return parent::setConversionCurrency($conversionCurrency);
    }

    /**
     * {@inheritDoc}
     */
    public function getConversionCurrency()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getConversionCurrency', array());

        return parent::getConversionCurrency();
    }

    /**
     * {@inheritDoc}
     */
    public function setBooking(\Mytrip\AdminBundle\Entity\Booking $booking = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBooking', array($booking));

        return parent::setBooking($booking);
    }

    /**
     * {@inheritDoc}
     */
    public function getBooking()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBooking', array());

        return parent::getBooking();
    }

    /**
     * {@inheritDoc}
     */
    public function setReservationPrice($reservationPrice)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReservationPrice', array($reservationPrice));

        return parent::setReservationPrice($reservationPrice);
    }

    /**
     * {@inheritDoc}
     */
    public function getReservationPrice()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReservationPrice', array());

        return parent::getReservationPrice();
    }

    /**
     * {@inheritDoc}
     */
    public function setReservationCharge($reservationCharge)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReservationCharge', array($reservationCharge));

        return parent::setReservationCharge($reservationCharge);
    }

    /**
     * {@inheritDoc}
     */
    public function getReservationCharge()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReservationCharge', array());

        return parent::getReservationCharge();
    }

    /**
     * {@inheritDoc}
     */
    public function setReservationTotalPrice($reservationTotalPrice)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReservationTotalPrice', array($reservationTotalPrice));

        return parent::setReservationTotalPrice($reservationTotalPrice);
    }

    /**
     * {@inheritDoc}
     */
    public function getReservationTotalPrice()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReservationTotalPrice', array());

        return parent::getReservationTotalPrice();
    }

    /**
     * {@inheritDoc}
     */
    public function setPaymenttype($paymenttype)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPaymenttype', array($paymenttype));

        return parent::setPaymenttype($paymenttype);
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymenttype()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPaymenttype', array());

        return parent::getPaymenttype();
    }

}
