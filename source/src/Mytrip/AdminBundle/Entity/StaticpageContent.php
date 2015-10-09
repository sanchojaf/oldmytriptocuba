<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaticpageContent
 */
class StaticpageContent
{
    /**
     * @var integer
     */
    private $staticpageContentId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $pageTitle;

    /**
     * @var string
     */
    private $metaDescription;

    /**
     * @var string
     */
    private $metaKeyword;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $lan;

    /**
     * @var \Mytrip\AdminBundle\Entity\Staticpage
     */
    private $staticpage;


    /**
     * Get staticpageContentId
     *
     * @return integer 
     */
    public function getStaticpageContentId()
    {
        return $this->staticpageContentId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return StaticpageContent
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
     * Set pageTitle
     *
     * @param string $pageTitle
     * @return StaticpageContent
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Get pageTitle
     *
     * @return string 
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return StaticpageContent
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeyword
     *
     * @param string $metaKeyword
     * @return StaticpageContent
     */
    public function setMetaKeyword($metaKeyword)
    {
        $this->metaKeyword = $metaKeyword;

        return $this;
    }

    /**
     * Get metaKeyword
     *
     * @return string 
     */
    public function getMetaKeyword()
    {
        return $this->metaKeyword;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return StaticpageContent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set lan
     *
     * @param string $lan
     * @return StaticpageContent
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
     * Set staticpage
     *
     * @param \Mytrip\AdminBundle\Entity\Staticpage $staticpage
     * @return StaticpageContent
     */
    public function setStaticpage(\Mytrip\AdminBundle\Entity\Staticpage $staticpage = null)
    {
        $this->staticpage = $staticpage;

        return $this;
    }

    /**
     * Get staticpage
     *
     * @return \Mytrip\AdminBundle\Entity\Staticpage 
     */
    public function getStaticpage()
    {
        return $this->staticpage;
    }
}
