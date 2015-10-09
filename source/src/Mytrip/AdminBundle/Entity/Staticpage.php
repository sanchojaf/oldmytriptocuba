<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Staticpage
 */
class Staticpage
{
    /**
     * @var integer
     */
    private $staticpageId;

    /**
     * @var string
     */
    private $pagename;

    /**
     * @var string
     */
    private $seo;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $createdDate;


    /**
     * Get staticpageId
     *
     * @return integer 
     */
    public function getStaticpageId()
    {
        return $this->staticpageId;
    }

    /**
     * Set pagename
     *
     * @param string $pagename
     * @return Staticpage
     */
    public function setPagename($pagename)
    {
        $this->pagename = $pagename;

        return $this;
    }

    /**
     * Get pagename
     *
     * @return string 
     */
    public function getPagename()
    {
        return $this->pagename;
    }

    /**
     * Set seo
     *
     * @param string $seo
     * @return Staticpage
     */
    public function setSeo($seo)
    {
        $this->seo = $seo;

        return $this;
    }

    /**
     * Get seo
     *
     * @return string 
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Staticpage
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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Staticpage
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
     * @var string
     */
    private $status;


    /**
     * Set status
     *
     * @param string $status
     * @return Staticpage
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @var string
     */
    private $mainMenu;

    /**
     * @var integer
     */
    private $menuId;


    /**
     * Set mainMenu
     *
     * @param string $mainMenu
     * @return Staticpage
     */
    public function setMainMenu($mainMenu)
    {
        $this->mainMenu = $mainMenu;

        return $this;
    }

    /**
     * Get mainMenu
     *
     * @return string 
     */
    public function getMainMenu()
    {
        return $this->mainMenu;
    }

    /**
     * Set menuId
     *
     * @param integer $menuId
     * @return Staticpage
     */
    public function setMenuId($menuId)
    {
        $this->menuId = $menuId;

        return $this;
    }

    /**
     * Get menuId
     *
     * @return integer 
     */
    public function getMenuId()
    {
        return $this->menuId;
    }
    /**
     * @var string
     */
    private $url;


    /**
     * Set url
     *
     * @param string $url
     * @return Staticpage
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
}
