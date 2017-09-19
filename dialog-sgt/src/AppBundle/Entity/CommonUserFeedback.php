<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/26/16
 * Time: 12:02 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * CommonUserFeedback
 *
 * @ORM\Table(name="common_user_feedback", indexes={@ORM\Index(name="fk_common_user1_idx", columns={"common_user_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\CommonUserFeedback")
 */
class CommonUserFeedback
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
     * @var \CommonUser
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="CommonUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="common_user_id", referencedColumnName="id")
     * })
     */
    private $commonUser;

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
     * @return CommonUserFeedback
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
     * @return CommonUserFeedback
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
     * @return CommonUserFeedback
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
     * @return CommonUserFeedback
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
     * @return CommonUserFeedback
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
     * Set commonUser
     *
     * @param \AppBundle\Entity\CommonUser $commonUser
     * @return CommonUserFeedback
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

    public function __construct()
    {
        $this->status = 1;
    }
}
