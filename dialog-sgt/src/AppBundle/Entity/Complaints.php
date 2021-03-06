<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Complaints
 *
 * @ORM\Table(name="complaints", indexes={@ORM\Index(name="fk_complaints_common_user1_idx", columns={"common_user_id"}), @ORM\Index(name="fk_complaints_vehicle1_idx", columns={"vehicle_id"}), @ORM\Index(name="fk_complaints_route1_idx", columns={"route_id"}), @ORM\Index(name="fk_complaints_owner_company1_idx", columns={"owner_company_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\Complaints")
 */
class Complaints
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
     * @var \Date
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="string", nullable=true)
     */
    private $isRead;

    /**
     * @var \CommonUser
     *
     * @ORM\ManyToOne(targetEntity="CommonUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="common_user_id", referencedColumnName="id")
     * })
     */
    private $commonUser;

    /**
     * @var \Vehicle
     *
     * @ORM\ManyToOne(targetEntity="Vehicle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id")
     * })
     */
    private $vehicle;

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
     * @var \OwnerCompany
     *
     * @ORM\ManyToOne(targetEntity="OwnerCompany")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_company_id", referencedColumnName="id")
     * })
     */
    private $ownerCompany;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var Time
     * 
     * @ORM\Column(name="time", type="time",  nullable=false)
     */
    private $time;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
        return $id;
    }



    /**
     * Set description
     *
     * @param string $description
     * @return Complaints
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
     * Set isRead
     *
     * @param boolean $isRead
     * @return Complaints
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean 
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set commonUser
     *
     * @param \AppBundle\Entity\CommonUser $commonUser
     * @return Complaints
     */
    public function setCommonUser(\AppBundle\Entity\CommonUser $commonUser = null)
    {
        $this->commonUser = $commonUser;

        return $this;
    }

    /**
     * Get commonUser
     *
     * @return \AppBundle\Entity\CommonUser 
     */
    public function getCommonUser()
    {
        return $this->commonUser;
    }

    /**
     * Set vehicle
     *
     * @param \AppBundle\Entity\Vehicle $vehicle
     * @return Complaints
     */
    public function setVehicle(\AppBundle\Entity\Vehicle $vehicle = null)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * Get vehicle
     *
     * @return \AppBundle\Entity\Vehicle 
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * Set route
     *
     * @param \AppBundle\Entity\Route $route
     * @return Complaints
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

    /**
     * Set ownerCompany
     *
     * @param \AppBundle\Entity\OwnerCompany $ownerCompany
     * @return Complaints
     */
    public function setOwnerCompany(\AppBundle\Entity\OwnerCompany $ownerCompany = null)
    {
        $this->ownerCompany = $ownerCompany;

        return $this;
    }

    /**
     * Get ownerCompany
     *
     * @return \AppBundle\Entity\OwnerCompany 
     */
    public function getOwnerCompany()
    {
        return $this->ownerCompany;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Complaints
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

    public function __construct()
    {
        $this->status = 1;
        $this->vehicle=null;
        $this->route=null;
        $this->ownerCompany=null;
        $this->isRead=1;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Complaints
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Complaints
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }
}
