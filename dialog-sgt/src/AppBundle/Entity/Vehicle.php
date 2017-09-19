<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Vehicle
 *
 * @ORM\Table(name="vehicle", indexes={@ORM\Index(name="fk_vehicle_owner_company1_idx", columns={"owner_company_id"})})
 * @ORM\Entity
 */
class Vehicle
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
     * @ORM\Column(name="vehicle_no", type="string", length=10, nullable=false)
     */
    private $vehicleNo;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="vehicle_brand", type="string", length=15, nullable=false)
     */
    private $vehicleBrand;

    /**
     * @var float
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="capacity", type="float", precision=10, scale=0, nullable=false)
     */
    private $capacity;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status=true;

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
     * Set vehicleNo
     *
     * @param string $vehicleNo
     * @return Vehicle
     */
    public function setVehicleNo($vehicleNo)
    {
        $this->vehicleNo = $vehicleNo;

        return $this;
    }

    /**
     * Get vehicleNo
     *
     * @return string 
     */
    public function getVehicleNo()
    {
        return $this->vehicleNo;
    }

    /**
     * Set vehicleBrand
     *
     * @param string $vehicleBrand
     * @return Vehicle
     */
    public function setVehicleBrand($vehicleBrand)
    {
        $this->vehicleBrand = $vehicleBrand;

        return $this;
    }

    /**
     * Get vehicleBrand
     *
     * @return string 
     */
    public function getVehicleBrand()
    {
        return $this->vehicleBrand;
    }

    /**
     * Set capacity
     *
     * @param float $capacity
     * @return Vehicle
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return float 
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Vehicle
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
     * Set ownerCompany
     *
     * @param \AppBundle\Entity\OwnerCompany $ownerCompany
     * @return Vehicle
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
        return $this->id != null ? $this->getVehicleNo() : 'New Record';
    }
}
