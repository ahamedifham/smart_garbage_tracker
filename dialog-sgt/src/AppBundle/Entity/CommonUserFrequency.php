<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/15/16
 * Time: 1:05 PM
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommonUserFrequency
 *
 * @ORM\Table(name="common_user_frequency")
 * @ORM\Entity
 */

class CommonUserFrequency
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
     * @ORM\Column(name="frequency", type="string", length=30, nullable=false)
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
     * Set frequency
     *
     * @param string $frequency
     * @return CommonUserFrequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return string 
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    public function __toString()
    {
        return $this->id != null ? $this->getFrequency() : 'New Record';
    }
}
