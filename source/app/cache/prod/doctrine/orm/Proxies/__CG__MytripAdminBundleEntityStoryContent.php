<?php

namespace Proxies\__CG__\Mytrip\AdminBundle\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class StoryContent extends \Mytrip\AdminBundle\Entity\StoryContent implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'storyContentId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'name', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'subHead', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'content', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'metaTitle', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'metaDescription', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'metaKeyword', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'lan', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'story');
        }

        return array('__isInitialized__', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'storyContentId', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'name', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'subHead', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'content', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'metaTitle', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'metaDescription', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'metaKeyword', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'lan', '' . "\0" . 'Mytrip\\AdminBundle\\Entity\\StoryContent' . "\0" . 'story');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (StoryContent $proxy) {
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
    public function getStoryContentId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getStoryContentId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStoryContentId', array());

        return parent::getStoryContentId();
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
    public function setSubHead($subHead)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubHead', array($subHead));

        return parent::setSubHead($subHead);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubHead()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubHead', array());

        return parent::getSubHead();
    }

    /**
     * {@inheritDoc}
     */
    public function setContent($content)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContent', array($content));

        return parent::setContent($content);
    }

    /**
     * {@inheritDoc}
     */
    public function getContent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContent', array());

        return parent::getContent();
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaTitle($metaTitle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMetaTitle', array($metaTitle));

        return parent::setMetaTitle($metaTitle);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaTitle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetaTitle', array());

        return parent::getMetaTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaDescription($metaDescription)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMetaDescription', array($metaDescription));

        return parent::setMetaDescription($metaDescription);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaDescription()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetaDescription', array());

        return parent::getMetaDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaKeyword($metaKeyword)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMetaKeyword', array($metaKeyword));

        return parent::setMetaKeyword($metaKeyword);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaKeyword()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetaKeyword', array());

        return parent::getMetaKeyword();
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
    public function setStory(\Mytrip\AdminBundle\Entity\Story $story = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStory', array($story));

        return parent::setStory($story);
    }

    /**
     * {@inheritDoc}
     */
    public function getStory()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStory', array());

        return parent::getStory();
    }

}
