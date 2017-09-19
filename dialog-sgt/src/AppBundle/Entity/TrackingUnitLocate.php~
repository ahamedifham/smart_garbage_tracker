<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/8/16
 * Time: 7:16 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * TrackingUnitLocate
 *
 * @ORM\Table(name="tracking_unit_locate", indexes={@ORM\Index(name="fk_tracking_unit_locate_tracking_unit1_idx", columns={"tracking_unit_id"}), @ORM\Index(name="fk_tacking_unit_locate_route1_idx", columns={"route_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\TrackingUnitLocate")
 */


class TrackingUnitLocate
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
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var \Route
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="Route")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="route_id", referencedColumnName="id")
     * })
     */
    private $route;

    /**
     * @var \TrackingUnit
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="TrackingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tracking_unit_id", referencedColumnName="id")
     * })
     */

    private $truck;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", precision=10, scale=0, nullable=true)
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", precision=10, scale=0, nullable=true)
     */
    private $lng;

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
     * Set name
     *
     * @param string $name
     * @return TrackingUnitLocate
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
     * Set route
     *
     * @param \AppBundle\Entity\Route $route
     * @return TrackingUnitLocate
     */
    public function setRoute(\AppBundle\Entity\Route $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \AppBundle\Entity\Route 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return TrackingUnitLocate
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return TrackingUnitLocate
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set truck
     *
     * @param \AppBundle\Entity\TrackingUnit $truck
     * @return TrackingUnitLocate
     */
    public function setTruck(\AppBundle\Entity\TrackingUnit $truck = null)
    {
        $this->truck = $truck;

        return $this;
    }

    /**
     * Get truck
     *
     * @return \AppBundle\Entity\TrackingUnit 
     */
    public function getTruck()
    {
        return $this->truck;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return TrackingUnitLocate
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
}
