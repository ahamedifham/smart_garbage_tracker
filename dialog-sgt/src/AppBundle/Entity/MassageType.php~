<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * MassageType
 *
 * @ORM\Table(name="massage_type", indexes={@ORM\Index(name="fk_massage_type_medium_idx", columns={"medium_id"}), @ORM\Index(name="fk_massage_type_category1_idx", columns={"category_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\MassageType")
 */
class MassageType
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
    private $status = true;

    /**
     * @var \Medium
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="Medium",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="medium_id", referencedColumnName="id")
     * })
     */
    private $medium;

    /**
     * @var \Category
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="Category",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="successor", type="string", length=150, nullable=true)
     */
    private $successor;


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
     * @return MassageType
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
     * Set medium
     *
     * @param \AppBundle\Entity\Medium $medium
     * @return MassageType
     */
    public function setMedium(\AppBundle\Entity\Medium $medium = null)
    {
        $this->medium = $medium;

        return $this;
    }

    /**
     * Get medium
     *
     * @return \AppBundle\Entity\Medium
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return MassageType
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set successor
     *
     * @param string $successor
     * @return Alert
     */

    public function setSuccessor($successor)
    {
        $this->successor = $successor;

        return $this;
    }

    /**
     * Get successor
     *
     * @return string
     */
    public function getSuccessor()
    {
        return $this->successor;
    }
}
