<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Massage
 *
 * @ORM\Table(name="massage", indexes={@ORM\Index(name="fk_massage_massage_type1_idx", columns={"massage_type_id"})},indexes={@ORM\Index(name="fk_massage_languages1_idx", columns={"languages_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\Massage")
 */
class Massage
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
     * @var \Languages
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="Languages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="languages_id", referencedColumnName="id")
     * })
     */
    private $languages;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="massage", type="string", length=150, nullable=false)
     */
    private $massage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status=true;

    /**
     * @var \MassageType
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="MassageType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="massage_type_id", referencedColumnName="id")
     * })
     */
    private $massageType;


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
     * Set languages
     *
     * @param \AppBundle\Entity\Languages $languages
     * @return Massage
     */
    public function setLanguage(\AppBundle\Entity\Languages $languages = null)
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Get languages
     *
     * @return \AppBundle\Entity\Languages
     */
    public function getLanguage()
    {
        return $this->languages;
    }

    /**
     * Set massage
     *
     * @param string $massage
     * @return Massage
     */
    public function setMassage($massage)
    {
        $this->massage = $massage;

        return $this;
    }

    /**
     * Get massage
     *
     * @return string 
     */
    public function getMassage()
    {
        return $this->massage;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Massage
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
     * Set massageType
     *
     * @param \AppBundle\Entity\MassageType $massageType
     * @return Massage
     */
    public function setMassageType(\AppBundle\Entity\MassageType $massageType = null)
    {
        $this->massageType = $massageType;

        return $this;
    }

    /**
     * Get massageType
     *
     * @return \AppBundle\Entity\MassageType
     */
    public function getMassageType()
    {
        return $this->massageType;
    }

    public function __toString()
    {
        return $this->id != null ? $this->getMassage() : 'New Record';
    }

    /**
     * Set languages
     *
     * @param \AppBundle\Entity\Languages $languages
     * @return Massage
     */
    public function setLanguages(\AppBundle\Entity\Languages $languages = null)
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Get languages
     *
     * @return \AppBundle\Entity\Languages 
     */
    public function getLanguages()
    {
        return $this->languages;
    }
}
