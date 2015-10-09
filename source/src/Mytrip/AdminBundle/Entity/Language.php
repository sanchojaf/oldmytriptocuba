<?php

namespace Mytrip\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 */
class Language
{
    /**
     * @var integer
     */
    private $languageId;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $lanCode;


    /**
     * Get languageId
     *
     * @return integer 
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Language
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set lanCode
     *
     * @param string $lanCode
     * @return Language
     */
    public function setLanCode($lanCode)
    {
        $this->lanCode = $lanCode;

        return $this;
    }

    /**
     * Get lanCode
     *
     * @return string 
     */
    public function getLanCode()
    {
        return $this->lanCode;
    }
    /**
     * @var string
     */
    private $flag;


    /**
     * Set flag
     *
     * @param string $flag
     * @return Language
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return string 
     */
    public function getFlag()
    {
        return $this->flag;
    }
}
