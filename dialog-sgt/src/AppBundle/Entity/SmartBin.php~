<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/3/16
 * Time: 1:16 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * SmartBin
 *
 * @ORM\Table(name="smart_bin", indexes={@ORM\Index(name="fk_smart_bin_route1_idx", columns={"route_id"}),  @ORM\Index(name="fk_smart_bin_smart_bin_bin_level_event1_idx", columns={"smart_bin_bin_level_event_id"}),  @ORM\Index(name="fk_smart_bin_smart_bin_bat_level_event1_idx", columns={"smart_bin_bat_level_event_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\SmartBin")
 * @UniqueEntity("serial")
 */

class SmartBin
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="serial", type="string", length=30, nullable=false, unique=true)
     */
    private $serial;

    /**
     * @var \Route
     * 
     * @ORM\ManyToOne(targetEntity="Route")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="route_id", referencedColumnName="id")
     * })
     */
    private $route;


    /**
     * @var \SmartBinBinLevelEvent
     * 
     * @ORM\ManyToOne(targetEntity="SmartBinBinLevelEvent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="smart_bin_bin_level_event_id", referencedColumnName="id")
     * })
     */
    private $smartBinBinLevelEvent;
    /**
     * @var \SmartBinBatLevelEvent
     * 
     * @ORM\ManyToOne(targetEntity="SmartBinBatLevelEvent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="smart_bin_bat_level_event_id", referencedColumnName="id")
     * })
     */
    private $smartBinBatLevelEvent;


    /**
     * @var integer
     *
     * @ORM\Column(name="battery_level", type="integer", nullable=true)
     */
    private $batteryLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="bin_level", type="integer", nullable=true)
     */
    private $binLevel;

    /**
     * @var float
     *
     * @ORM\Column(name="smart_bin_point_lat", type="float", precision=10, scale=0, nullable=true)
     */
    private $smartBinPointLat;

    /**
     * @var float
     *
     * @ORM\Column(name="smart_bin_point_lng", type="float", precision=10, scale=0, nullable=true)
     */
    private $smartBinPointLng;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status=true;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="user", type="string", length=30, nullable=false)
     */
    private $user;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="user_email", type="string", length=30, nullable=false)
     */
    private $userEmail;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Regex("/([+]{1}[94]{2}[0-9]{9})/")
     * @ORM\Column(name="user_mobile_one", type="string", length=12, nullable=true)
     */
    private $userMobileOne;

    /**
     * @var string
     * @Assert\Regex("/([+]{1}[94]{2}[0-9]{9})/")
     * @ORM\Column(name="user_mobile_two", type="string", length=12, nullable=true)
     */
    private $userMobileTwo;

    /**
     * @var string
     * @Assert\Regex("/([+]{1}[94]{2}[0-9]{9})/")
     * @ORM\Column(name="user_mobile_three", type="string", length=12, nullable=true)
     */
    private $userMobileThree;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="user_address", type="string", length=30, nullable=true)
     */
    private $userAddress;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="description", type="string", length=50, nullable=true)
     */
    private $description;

    
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
     * Set serial
     *
     * @param string $serial
     * @return SmartBin
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * Get serial
     *
     * @return string 
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Set batteryLevel
     *
     * @param integer $batteryLevel
     * @return SmartBin
     */
    public function setBatteryLevel($batteryLevel)
    {
        $this->batteryLevel = $batteryLevel;

        return $this;
    }

    /**
     * Get batteryLevel
     *
     * @return integer 
     */
    public function getBatteryLevel()
    {
        return $this->batteryLevel;
    }

    /**
     * Set binLevel
     *
     * @param integer $binLevel
     * @return SmartBin
     */
    public function setBinLevel($binLevel)
    {
        $this->binLevel = $binLevel;

        return $this;
    }

    /**
     * Get binLevel
     *
     * @return integer 
     */
    public function getBinLevel()
    {
        return $this->binLevel;
    }

    /**
     * Set smartBinPointLat
     *
     * @param float $smartBinPointLat
     * @return SmartBin
     */
    public function setSmartBinPointLat($smartBinPointLat)
    {
        $this->smartBinPointLat = $smartBinPointLat;

        return $this;
    }

    /**
     * Get smartBinPointLat
     *
     * @return float 
     */
    public function getSmartBinPointLat()
    {
        return $this->smartBinPointLat;
    }

    /**
     * Set smartBinPointLng
     *
     * @param float $smartBinPointLng
     * @return SmartBin
     */
    public function setSmartBinPointLng($smartBinPointLng)
    {
        $this->smartBinPointLng = $smartBinPointLng;

        return $this;
    }

    /**
     * Get smartBinPointLng
     *
     * @return float 
     */
    public function getSmartBinPointLng()
    {
        return $this->smartBinPointLng;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return SmartBin
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
//
//    /**
//     * Set route
//     *
//     * @param \AppBundle\Entity\Route $route
//     * @return SmartBin
//     */
//    public function setRoute(\AppBundle\Entity\Route $route = null)
//    {
//        $this->route = $route;
//
//        return $this;
//    }
//
//    /**
//     * Get route
//     *
//     * @return \AppBundle\Entity\Route 
//     */
//    public function getRoute()
//    {
//        return $this->route;
//    }

    public function __construct()
    {
        $this->status = 1;
        $this->smartBinPointLat=null;
        $this->smartBinPointLng=null;
        $this->batteryLevel=0;
        $this->binLevel= 0;
        $this->userMobileTwo=null;
        $this->userMobileThree=null;
       // $this->route=null;
        //$this->smartBinBinLevelEvent=;
       //$this->smartBinBatLevelEvent=null;
    }


    /**
     * Set user
     *
     * @param string $user
     * @return SmartBin
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     * @return SmartBin
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string 
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set smartBinBinLevelEvent
     *
     * @param \AppBundle\Entity\SmartBinBinLevelEvent $smartBinBinLevelEvent
     * @return SmartBin
     */
    public function setSmartBinBinLevelEvent(\AppBundle\Entity\SmartBinBinLevelEvent $smartBinBinLevelEvent = null)
    {
        $this->smartBinBinLevelEvent = $smartBinBinLevelEvent;

        return $this;
    }

    /**
     * Get smartBinBinLevelEvent
     *
     * @return \AppBundle\Entity\SmartBinBinLevelEvent 
     */
    public function getSmartBinBinLevelEvent()
    {
        return $this->smartBinBinLevelEvent;
    }

    /**
     * Set smartBinBatLevelEvent
     *
     * @param \AppBundle\Entity\SmartBinBatLevelEvent $smartBinBatLevelEvent
     * @return SmartBin
     */
    public function setSmartBinBatLevelEvent(\AppBundle\Entity\SmartBinBatLevelEvent $smartBinBatLevelEvent = null)
    {
        $this->smartBinBatLevelEvent = $smartBinBatLevelEvent;

        return $this;
    }

    /**
     * Get smartBinBatLevelEvent
     *
     * @return \AppBundle\Entity\SmartBinBatLevelEvent 
     */
    public function getSmartBinBatLevelEvent()
    {
        return $this->smartBinBatLevelEvent;
    }

    /**
     * Set userMobileOne
     *
     * @param string $userMobileOne
     * @return SmartBin
     */
    public function setUserMobileOne($userMobileOne)
    {
        $this->userMobileOne = $userMobileOne;

        return $this;
    }

    /**
     * Get userMobileOne
     *
     * @return string 
     */
    public function getUserMobileOne()
    {
        return $this->userMobileOne;
    }

    /**
     * Set userMobileTwo
     *
     * @param string $userMobileTwo
     * @return SmartBin
     */
    public function setUserMobileTwo($userMobileTwo)
    {
        $this->userMobileTwo = $userMobileTwo;

        return $this;
    }

    /**
     * Get userMobileTwo
     *
     * @return string 
     */
    public function getUserMobileTwo()
    {
        return $this->userMobileTwo;
    }

    /**
     * Set userMobileThree
     *
     * @param string $userMobileThree
     * @return SmartBin
     */
    public function setUserMobileThree($userMobileThree)
    {
        $this->userMobileThree = $userMobileThree;

        return $this;
    }

    /**
     * Get userMobileThree
     *
     * @return string 
     */
    public function getUserMobileThree()
    {
        return $this->userMobileThree;
    }

    /**
     * Set userAddress
     *
     * @param string $userAddress
     * @return SmartBin
     */
    public function setUserAddress($userAddress)
    {
        $this->userAddress = $userAddress;

        return $this;
    }

    /**
     * Get userAddress
     *
     * @return string 
     */
    public function getUserAddress()
    {
        return $this->userAddress;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SmartBin
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }



    /**
     * Set route
     *
     * @param \AppBundle\Entity\Route $route
     * @return SmartBin
     */
    public function setRoute(\AppBundle\Entity\Route $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \AppBundle\Entity\Route 
     */
    public function getRoute()
    {
        return $this->route;
    }
}
