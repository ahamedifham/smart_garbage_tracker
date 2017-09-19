<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/21/16
 * Time: 1:50 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Schedule
 *
 * @ORM\Table(name="schedule", indexes={@ORM\Index(name="fk_schedule_driver1_idx", columns={"driver_id"}), @ORM\Index(name="fk_schedule_route1_idx", columns={"route_id"}), @ORM\Index(name="fk_schedule_tracking_unit1_idx", columns={"tracking_unit_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\Schedule")
 * @UniqueEntity("name")
 */

class Schedule
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
     * @ORM\Column(name="name", type="string", length=30, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var \Driver
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="Driver")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="driver_id", referencedColumnName="id")
     * })
     */
    private $driver;

    /**
     * @var \Route
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="Route")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="route_id", referencedColumnName="id")
     * })
     */
    private $route;

    /**
     * @var \TrackingUnit
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="TrackingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tracking_unit_id", referencedColumnName="id")
     * })
     */
    private $truck;



    /**
     * @ORM\ManyToMany(targetEntity="Weekdays", inversedBy="Weekdays")
     * @ORM\JoinTable(name="dayName")
     */
    private $weekday;


    /**
     * @var string
     *
     * @ORM\Column(name="supervisor", type="string", length=30, nullable=false)
     */
    private $supervisor;

    /**
     * @var Date
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="date", type="date",  nullable=false)
     */
    private $date;

    /**
     * @var Time
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="startTime", type="time",  nullable=false)
     */
    private $startTime;

    /**
     * @var Time
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="endTime", type="time",  nullable=false)
     */
    private $endTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="repeat1", type="boolean", nullable=false)
     */
    private $repeat1;



    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
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
     * Set name
     *
     * @param string $name
     * @return Schedule
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
     * Set supervisor
     *
     * @param string $supervisor
     * @return Schedule
     */
    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * Get supervisor
     *
     * @return string 
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }



    /**
     * Set status
     *
     * @param boolean $status
     * @return Schedule
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

    /**
     * Set driver
     *
     * @param \AppBundle\Entity\Driver $driver
     * @return Schedule
     */
    public function setDriver(\AppBundle\Entity\Driver $driver = null)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver
     *
     * @return \AppBundle\Entity\Driver 
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set route
     *
     * @param \AppBundle\Entity\Route $route
     * @return Schedule
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
     * Set truck
     *
     * @param \AppBundle\Entity\TrackingUnit $truck
     * @return Schedule
     */
    public function setTruck(\AppBundle\Entity\TrackingUnit $truck = null)
    {
        $this->truck = $truck;

        return $this;
    }

    /**
     * Get truck
     *
     * @return \AppBundle\Entity\TrackingUnit 
     */
    public function getTruck()
    {
        return $this->truck;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Schedule
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



    public function __toString()
    {
        return $this->id != null ? $this->getName() : 'New Record';
    }

    public function __construct()
    {
        $this->status = 1;
    }



    /**
     * Set repeat1
     *
     * @param boolean $repeat1
     * @return Schedule
     */
    public function setRepeat1($repeat1)
    {
        $this->repeat1 = $repeat1;

        return $this;
    }

    /**
     * Get repeat1
     *
     * @return boolean 
     */
    public function getRepeat1()
    {
        return $this->repeat1;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Schedule
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Schedule
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

   



    /**
     * Add weekday
     *
     * @param \AppBundle\Entity\Weekdays $weekday
     * @return Schedule
     */
    public function addWeekday(\AppBundle\Entity\Weekdays $weekday)
    {
        $this->weekday[] = $weekday;

        return $this;
    }

    /**
     * Remove weekday
     *
     * @param \AppBundle\Entity\Weekdays $weekday
     */
    public function removeWeekday(\AppBundle\Entity\Weekdays $weekday)
    {
        $this->weekday->removeElement($weekday);
    }

    /**
     * Get weekday
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWeekday()
    {
        return $this->weekday;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Schedule
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
}
