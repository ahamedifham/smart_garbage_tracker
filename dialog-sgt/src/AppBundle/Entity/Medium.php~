<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Medium
 *
 * @ORM\Table(name="medium")
 * @ORM\Entity
 */
class Medium
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
     * @ORM\Column(name="mediumType", type="string", length=30, nullable=false)
     */
    private $mediumType;

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
     * Set mediumType
     *
     * @param string $mediumType
     * @return Medium
     */
    public function setMediumType($mediumType)
    {
        $this->mediumType = $mediumType;

        return $this;
    }

    /**
     * Get mediumType
     *
     * @return string
     */
    public function getMediumType()
    {
        return $this->mediumType;
    }
    public function __toString()
    {
        return $this->id != null ? $this->getMediumType() : 'New Record';
    }
}
