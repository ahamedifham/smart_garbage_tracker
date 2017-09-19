<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EndPointVisibility
 *
 * @ORM\Table(name="end_point_visibility", indexes={@ORM\Index(name="fk_end_point_visibility_owner_company1_idx", columns={"owner_company_id"}), @ORM\Index(name="fk_end_point_visibility_end_point1_idx", columns={"end_point_id"})})
 * @ORM\Entity
 */
class EndPointVisibility
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
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var \OwnerCompany
     *
     * @ORM\ManyToOne(targetEntity="OwnerCompany")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_company_id", referencedColumnName="id")
     * })
     */
    private $ownerCompany;

    /**
     * @var \EndPoint
     *
     * @ORM\ManyToOne(targetEntity="EndPoint")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="end_point_id", referencedColumnName="id")
     * })
     */
    private $endPoint;



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
     * Set status
     *
     * @param boolean $status
     * @return EndPointVisibility
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
     * @return EndPointVisibility
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

    /**
     * Set endPoint
     *
     * @param \AppBundle\Entity\EndPoint $endPoint
     * @return EndPointVisibility
     */
    public function setEndPoint(\AppBundle\Entity\EndPoint $endPoint = null)
    {
        $this->endPoint = $endPoint;

        return $this;
    }

    /**
     * Get endPoint
     *
     * @return \AppBundle\Entity\EndPoint 
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }
}
