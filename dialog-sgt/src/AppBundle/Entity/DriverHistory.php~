<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 12/8/16
 * Time: 5:04 PM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * DriverHistory
 *
 * @ORM\Table(name="driver_history", indexes={@ORM\Index(name="fk_driver_history_schedule1_idx", columns={"schedule_id"}), @ORM\Index(name="fk_driver_history_tracking_unit1_idx", columns={"tracking_unit_id"}), @ORM\Index(name="fk_driver_history_route1_idx", columns={"route_id"}), @ORM\Index(name="fk_driver_history_driver1_idx", columns={"driver_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\DriverHistory")
 */

class DriverHistory
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
     * @var Time
     *
     * @ORM\Column(name="start_time", type="time",  nullable=true)
     */
    private $startTime;

    /**
     * @var Time
     *
     * @ORM\Column(name="end_time", type="time",  nullable=true)
     */
    private $endTime;

    /**
     * @var Date
     *
     * @ORM\Column(name="date", type="date",  nullable=false)
     */
    private $date;

    /**
     * @var \Driver
     *
     * @ORM\ManyToOne(targetEntity="Driver")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="driver_id", referencedColumnName="id")
     * })
     */
    private $driver;

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
     * @var \Schedule
     *
     * @ORM\ManyToOne(targetEntity="Schedule")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="schedule_id", referencedColumnName="id")
     * })
     */
    private $schedule;

    /**
     * @var \TrackingUnit
     *
     * @ORM\ManyToOne(targetEntity="TrackingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tracking_unit_id", referencedColumnName="id")
     * })
     */
    private $trackingUnit;



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
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return DriverHistory
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
     * @return DriverHistory
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
     * Set date
     *
     * @param \DateTime $date
     * @return DriverHistory
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
     * Set driver
     *
     * @param \AppBundle\Entity\Driver $driver
     * @return DriverHistory
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
     * @return DriverHistory
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
     * Set schedule
     *
     * @param \AppBundle\Entity\Schedule $schedule
     * @return DriverHistory
     */
    public function setSchedule(\AppBundle\Entity\Schedule $schedule = null)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     *
     * @return \AppBundle\Entity\Schedule 
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Set trackingUnit
     *
     * @param \AppBundle\Entity\TrackingUnit $trackingUnit
     * @return DriverHistory
     */
    public function setTrackingUnit(\AppBundle\Entity\TrackingUnit $trackingUnit = null)
    {
        $this->trackingUnit = $trackingUnit;

        return $this;
    }

    /**
     * Get trackingUnit
     *
     * @return \AppBundle\Entity\TrackingUnit 
     */
    public function getTrackingUnit()
    {
        return $this->trackingUnit;
    }
}
