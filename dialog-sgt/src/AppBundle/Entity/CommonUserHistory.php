<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 12/8/16
 * Time: 11:11 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Complaints
 *
 * @ORM\Table(name="common_user_history", indexes={@ORM\Index(name="fk_common_user_history_common_user1_idx", columns={"common_user_id"}), @ORM\Index(name="fk_common_user_history_driver1_idx", columns={"driver_id"}), @ORM\Index(name="fk_common_user_history_schedule1_idx", columns={"schedule_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\CommonUserHistory")
 */

class CommonUserHistory
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
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_collected", type="boolean", nullable=true)
     */
    private $isCollected;

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
     * @var \Driver
     *
     * @ORM\ManyToOne(targetEntity="Driver")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="driver_id", referencedColumnName="id")
     * })
     */
    private $driver;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return CommonUserHistory
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
     * Set isCollected
     *
     * @param boolean $isCollected
     * @return CommonUserHistory
     */
    public function setIsCollected($isCollected)
    {
        $this->isCollected = $isCollected;

        return $this;
    }

    /**
     * Get isCollected
     *
     * @return boolean 
     */
    public function getIsCollected()
    {
        return $this->isCollected;
    }

    /**
     * Set commonUser
     *
     * @param \AppBundle\Entity\CommonUser $commonUser
     * @return CommonUserHistory
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
     * Set driver
     *
     * @param \AppBundle\Entity\Driver $driver
     * @return CommonUserHistory
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
     * Set schedule
     *
     * @param \AppBundle\Entity\Schedule $schedule
     * @return CommonUserHistory
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
}
