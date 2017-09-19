<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Driver
 *
 * @ORM\Table(name="driver", indexes={@ORM\Index(name="fk_driver_owner_company1_idx", columns={"owner_company_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\Driver")
 * @UniqueEntity("contact")
 */
class Driver
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
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @Assert\Regex("/([+]{1}[94]{2}[0-9]{9})/")
     * @ORM\Column(name="contact", type="string", length=12, nullable=false, unique=true)
     */
    private $contact;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="subscriber_id", type="string", length=20, nullable=false)
     */
    private $subscriberId;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=15, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt='1234';

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
     * Set name
     *
     * @param string $name
     * @return Driver
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
     * Set subscriberId
     *
     * @param string $subscriberId
     * @return Driver
     */
    public function setSubscriberId($subscriberId)
    {
        $this->subscriberId = $subscriberId;

        return $this;
    }

    /**
     * Get subscriberId
     *
     * @return string 
     */
    public function getSubscriberId()
    {
        return $this->subscriberId;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Driver
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Driver
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Driver
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
     * @return Driver
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
     * Set contact
     *
     * @param string $contact
     * @return Driver
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Driver
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function __toString()
    {
        return $this->id != null ? $this->getName() : 'New Record';
    }
}
