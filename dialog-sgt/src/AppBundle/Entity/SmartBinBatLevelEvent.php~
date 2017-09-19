<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/3/16
 * Time: 4:15 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * SmartBinEvents
 *
 * @ORM\Table(name="smart_bin_bat_level_event")
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\SmartBinBatLevelEvent")
 */

class SmartBinBatLevelEvent
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
     * @ORM\Column(name="event", type="string", length=20, nullable=false)
     */
    private $event;

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
     * Set event
     *
     * @param string $event
     * @return SmartBinBatLevelEvent
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return string 
     */
    public function getEvent()
    {
        return $this->event;
    }

    public function __toString()
    {
        return $this->id != null ? $this->getEvent() : 'New Record';
    }
}
