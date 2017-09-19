<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TrackingUnit
 *
 * @ORM\Table(name="tracking_unit", indexes={@ORM\Index(name="fk_tracking_unit_vehicle_idx", columns={"vehicle_id"}), @ORM\Index(name="fk_tracking_unit_driver1_idx", columns={"driver_id"}), @ORM\Index(name="fk_tracking_unit_route1_idx", columns={"route_id"}), @ORM\Index(name="fk_tracking_unit_owner_company1_idx", columns={"owner_company_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\TrackingUnit")
 * @UniqueEntity("msisdn")
 */
class TrackingUnit
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
     * @ORM\Column(name="msisdn", type="string", length=20, nullable=false, unique=true)
     */
    private $msisdn;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status=true;

    /**
     * @var \Vehicle
     *
     * @ORM\ManyToOne(targetEntity="Vehicle",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id")
     * })
     */
    private $vehicle;

    /**
     * @var \Driver
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="Driver")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="driver_id", referencedColumnName="id")
     * })
     */
    private $driver;

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
     * @var \OwnerCompany
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="OwnerCompany")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_company_id", referencedColumnName="id")
     * })
     */
    private $ownerCompany;



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
     * Set msisdn
     *
     * @param string $msisdn
     * @return TrackingUnit
     */
    public function setMsisdn($msisdn)
    {
        $this->msisdn = $msisdn;

        return $this;
    }

    /**
     * Get msisdn
     *
     * @return string 
     */
    public function getMsisdn()
    {
        return $this->msisdn;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return TrackingUnit
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
     * Set vehicle
     *
     * @param \AppBundle\Entity\Vehicle $vehicle
     * @return TrackingUnit
     */
    public function setVehicle(\AppBundle\Entity\Vehicle $vehicle = null)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * Get vehicle
     *
     * @return \AppBundle\Entity\Vehicle 
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * Set driver
     *
     * @param \AppBundle\Entity\Driver $driver
     * @return TrackingUnit
     */
    public function setDriver(\AppBundle\Entity\Driver $driver = null)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver
     *
     * @return \AppBundle\Entity\Driver 
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set route
     *
     * @param \AppBundle\Entity\Route $route
     * @return TrackingUnit
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
     * Set ownerCompany
     *
     * @param \AppBundle\Entity\OwnerCompany $ownerCompany
     * @return TrackingUnit
     */
    public function setOwnerCompany(\AppBundle\Entity\OwnerCompany $ownerCompany = null)
    {
        $this->ownerCompany = $ownerCompany;

        return $this;
    }

    /**
     * Get ownerCompany
     *
     * @return \AppBundle\Entity\OwnerCompany 
     */
    public function getOwnerCompany()
    {
        return $this->ownerCompany;
    }
    public function __toString()
    {
        return $this->id != null ? $this->getMsisdn() : 'New Record';
    }
}
