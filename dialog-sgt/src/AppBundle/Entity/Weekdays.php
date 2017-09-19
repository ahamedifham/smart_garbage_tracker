<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/25/16
 * Time: 12:31 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OwnerCompany
 *
 * @ORM\Table(name="weekdays")
 * @ORM\Entity
 */
class Weekdays
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
     * @ORM\Column(name="day_name", type="string", length=30, nullable=false)
     */
    private $dayName;


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
     * Set dayName
     *
     * @param string $dayName
     * @return Weekdays
     */
    public function setDayName($dayName)
    {
        $this->dayName = $dayName;

        return $this;
    }

    /**
     * Get dayName
     *
     * @return string 
     */
    public function getDayName()
    {
        return $this->dayName;
    }

    public function __toString()
    {
        return $this->id != null ? $this->getDayName() : 'New Record';
    }
}
