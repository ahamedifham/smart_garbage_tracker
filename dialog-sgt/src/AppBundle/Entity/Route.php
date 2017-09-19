<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Route
 *
 * @ORM\Table(name="route", indexes={@ORM\Index(name="fk_route_start_point1_idx", columns={"start_point_id"}), @ORM\Index(name="fk_route_end_point1_idx", columns={"end_point_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\Route")
 * @UniqueEntity("name")
 */
class Route
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
     * @ORM\Column(name="code", type="string", length=15, nullable=false)
     */
    private $code;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var float
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="start_point_lat", type="float", precision=10, scale=0, nullable=false)
     */
    private $startPointLat;

    /**
     * @var float
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="start_point_lng", type="float", precision=10, scale=0, nullable=false)
     */
    private $startPointLng;

    /**
     * @var float
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="end_point_lat", type="float", precision=10, scale=0, nullable=false)
     */
    private $endPointLat;

    /**
     * @var float
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="end_point_lng", type="float", precision=10, scale=0, nullable=false)
     */
    private $endPointLng;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="route_id", type="string", length=30, nullable=false)
     */
    private $routeId;

    /**
     * @var boolean
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var \StartPoint
     *
     * @ORM\ManyToOne(targetEntity="StartPoint")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="start_point_id", referencedColumnName="id")
     * })
     */
    private $startPoint;

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
     * Route constructor.
     * @param float $startPointLat
     * @param float $startPointLng
     * @param float $endPointLat
     * @param float $endPointLng
     * @param bool $status
     */
    public function __construct()
    {
        $this->startPointLat = 0;
        $this->startPointLng = 0;
        $this->endPointLat = 0;
        $this->endPointLng = 0;
        $this->status = 1;
        $this->code=1;
        $this->routeId = 0;
    }


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
     * Set code
     *
     * @param string $code
     * @return Route
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Route
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
     * Set startPointLat
     *
     * @param float $startPointLat
     * @return Route
     */
    public function setStartPointLat($startPointLat = 0)
    {
        $this->startPointLat = $startPointLat;

        return $this;
    }

    /**
     * Get startPointLat
     *
     * @return float 
     */
    public function getStartPointLat()
    {
        return $this->startPointLat;
    }

    /**
     * Set startPointLng
     *
     * @param float $startPointLng
     * @return Route
     */
    public function setStartPointLng($startPointLng = 0)
    {
        $this->startPointLng = $startPointLng;

        return $this;
    }

    /**
     * Get startPointLng
     *
     * @return float 
     */
    public function getStartPointLng()
    {
        return $this->startPointLng;
    }

    /**
     * Set endPointLat
     *
     * @param float $endPointLat
     * @return Route
     */
    public function setEndPointLat($endPointLat = 0 )
    {
        $this->endPointLat = $endPointLat;

        return $this;
    }

    /**
     * Get endPointLat
     *
     * @return float 
     */
    public function getEndPointLat()
    {
        return $this->endPointLat;
    }

    /**
     * Set endPointLng
     *
     * @param float $endPointLng
     * @return Route
     */
    public function setEndPointLng($endPointLng = 0)
    {
        $this->endPointLng = $endPointLng;

        return $this;
    }

    /**
     * Get endPointLng
     *
     * @return float 
     */
    public function getEndPointLng()
    {
        return $this->endPointLng;
    }

    /**
     * Set routeId
     *
     * @param string $routeId
     * @return Route
     */
    public function setRouteId($routeId)
    {
        $this->routeId = $routeId;

        return $this;
    }

    /**
     * Get routeId
     *
     * @return string 
     */
    public function getRouteId()
    {
        return $this->routeId;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Route
     */
    public function setStatus($status = 1)
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
     * Set startPoint
     *
     * @param \AppBundle\Entity\StartPoint $startPoint
     * @return Route
     */
    public function setStartPoint(\AppBundle\Entity\StartPoint $startPoint = Null)
    {
        $this->startPoint = $startPoint;

        return $this;
    }

    /**
     * Get startPoint
     *
     * @return \AppBundle\Entity\StartPoint 
     */
    public function getStartPoint()
    {
        return $this->startPoint;
    }

    /**
     * Set endPoint
     *
     * @param \AppBundle\Entity\EndPoint $endPoint
     * @return Route
     */
    public function setEndPoint(\AppBundle\Entity\EndPoint $endPoint = Null)
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

    public function __toString()
    {
        return $this->id != null ? $this->getName() : 'New Record';
    }
}
