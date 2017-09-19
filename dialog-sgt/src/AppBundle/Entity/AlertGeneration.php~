<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/15/16
 * Time: 5:35 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AlertGeneration
 *
 * @ORM\Table(name="alert_generation" , indexes={@ORM\Index(name="fk_alert_generation_type1_idx", columns={"type_id"})}))
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\AlertGeneration")
 */
class AlertGeneration
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
     * @var \CommonUserTypes
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="CommonUserTypes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */

    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=160, nullable=true)
     */
    private $message;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status=true;
    

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
     * Set message
     *
     * @param string $message
     * @return AlertGeneration
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return AlertGeneration
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
     * Set type
     *
     * @param \AppBundle\Entity\CommonUserTypes $type
     * @return AlertGeneration
     */
    public function setType(\AppBundle\Entity\CommonUserTypes $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\CommonUserTypes 
     */
    public function getType()
    {
        return $this->type;
    }

    public function __construct()
    {
        $this->status = 1;
    }
}
