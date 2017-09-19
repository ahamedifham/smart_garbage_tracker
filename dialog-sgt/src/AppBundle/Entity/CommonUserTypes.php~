<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/15/16
 * Time: 5:36 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CommonUserTypes
 *
 * @ORM\Table(name="common_user_types")
 * @ORM\Entity
 */

class CommonUserTypes
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
     * @ORM\Column(name="type", type="string", length=30, nullable=false)
     */
    private $type;
    

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
     * Set type
     *
     * @param string $type
     * @return CommonUserTypes
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    public function __toString()
    {
        return $this->id != null ? $this->getType() : 'New Record';
    }
}
