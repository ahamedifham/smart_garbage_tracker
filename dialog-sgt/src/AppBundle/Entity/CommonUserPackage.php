<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/15/16
 * Time: 12:47 PM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * CommonUserPackage
 *
 * @ORM\Table(name="common_user_package", indexes={@ORM\Index(name="fk_common_user_package_frequency1_idx", columns={"frequency_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\CommonUserPackage")
 * @UniqueEntity("name")
 */

class CommonUserPackage
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
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=50, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="fee", type="string", length=20, nullable=true)
     */
    private $fee;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_percentage", type="string", length=20, nullable=true)
     */
    private $taxPercentage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status=true;

    /**
     * @var \CommonUserFrequency
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="CommonUserFrequency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="frequency_id", referencedColumnName="id")
     * })
     */
    private $frequency;


    

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
     * @return CommonUserPackage
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
     * Set description
     *
     * @param string $description
     * @return CommonUserPackage
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
     * Set fee
     *
     * @param string $fee
     * @return CommonUserPackage
     */
    public function setFee($fee)
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * Get fee
     *
     * @return string 
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Set taxPercentage
     *
     * @param string $taxPercentage
     * @return CommonUserPackage
     */
    public function setTaxPercentage($taxPercentage)
    {
        $this->taxPercentage = $taxPercentage;

        return $this;
    }

    /**
     * Get taxPercentage
     *
     * @return string 
     */
    public function getTaxPercentage()
    {
        return $this->taxPercentage;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return CommonUserPackage
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
    }

    /**
     * Set frequency
     *
     * @param \AppBundle\Entity\CommonUserFrequency $frequency
     * @return CommonUserPackage
     */
    public function setFrequency(\AppBundle\Entity\CommonUserFrequency $frequency = null)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return \AppBundle\Entity\CommonUserFrequency 
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    public function __toString()
    {
        return $this->id != null ? $this->getName() : 'New Record';
    }
    
}
