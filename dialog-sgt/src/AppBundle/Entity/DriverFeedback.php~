<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/27/16
 * Time: 11:27 AM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * DriverFeedback
 *
 * @ORM\Table(name="driver_feedback", indexes={@ORM\Index(name="fk_driver1_idx", columns={"driver_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\DriverFeedback")
 */

class DriverFeedback
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
     * @var integer
     *
     * @ORM\Column(name="rate", type="integer", nullable=false)
     */
    private $rate;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="feedback", type="string", length=300, nullable=false)
     */
    private $feedback;

    /**
     * @var Date
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="date", type="date",  nullable=false)
     */
    private $date;


    /**
     * @var Time
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="time", type="time",  nullable=false)
     */
    private $time;

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
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


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
     * Set rate
     *
     * @param integer $rate
     * @return DriverFeedback
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set feedback
     *
     * @param string $feedback
     * @return DriverFeedback
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * Get feedback
     *
     * @return string 
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return DriverFeedback
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
     * @return DriverFeedback
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

    /**
     * Set status
     *
     * @param boolean $status
     * @return DriverFeedback
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
     * @return DriverFeedback
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

    public function __construct()
    {
        $this->status = 1;
    }
}
