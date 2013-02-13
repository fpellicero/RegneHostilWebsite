<?php

namespace RegneHostil\KingdomsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Camp
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Camp
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

     /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=10)
     */
     private $lang;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=100)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="desc", type="text")
     */
    private $desc;

    /**
    * @var string
    *
    * @ORM\Column(name="relic_name", type="string", length=100)
    */
    private $relic_name;

    /**
    * @var string
    *
    * @ORM\Column(name="relic_desc", type="text")
    */
    private $relic_desc;

    /**
    * @var string
    *
    * @ORM\Column(name="motto", type="string", length=50)
    */
    private $motto;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Camp
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
     * Set logo
     *
     * @param string $logo
     * @return Camp
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    
        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set desc
     *
     * @param string $desc
     * @return Camp
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    
        return $this;
    }

    /**
     * Get desc
     *
     * @return string 
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set relic_name
     *
     * @param string $relicName
     * @return Camp
     */
    public function setRelicName($relicName)
    {
        $this->relic_name = $relicName;
    
        return $this;
    }

    /**
     * Get relic_name
     *
     * @return string 
     */
    public function getRelicName()
    {
        return $this->relic_name;
    }

    /**
     * Set relic_desc
     *
     * @param string $relicDesc
     * @return Camp
     */
    public function setRelicDesc($relicDesc)
    {
        $this->relic_desc = $relicDesc;
    
        return $this;
    }

    /**
     * Get relic_desc
     *
     * @return string 
     */
    public function getRelicDesc()
    {
        return $this->relic_desc;
    }

    /**
     * Set motto
     *
     * @param string $motto
     * @return Camp
     */
    public function setMotto($motto)
    {
        $this->motto = $motto;
    
        return $this;
    }

    /**
     * Get motto
     *
     * @return string 
     */
    public function getMotto()
    {
        return $this->motto;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return Camp
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    
        return $this;
    }

    /**
     * Get lang
     *
     * @return string 
     */
    public function getLang()
    {
        return $this->lang;
    }
}