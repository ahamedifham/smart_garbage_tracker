<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * SystemUser
 *
 * @ORM\Table(name="system_user", indexes={@ORM\Index(name="fk_system_user_system_user_role1_idx", columns={"system_user_role_id"}), @ORM\Index(name="fk_system_user_owner_company1_idx", columns={"owner_company_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\SystemUser")
 * @UniqueEntity("username")
 */
class SystemUser implements UserInterface, \Serializable
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
     *  @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="username", type="string", length=15, nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string
     * @Assert\NotBlank(message="not_blank")
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status=true;

    /**
     * @var \SystemUserRole
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="SystemUserRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="system_user_role_id", referencedColumnName="id")
     * })
     */
    private $systemUserRole;

    /**
     * @var \OwnerCompany
     * @Assert\NotBlank(message="not_blank")
     * @ORM\ManyToOne(targetEntity="OwnerCompany")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner_company_id", referencedColumnName="id")
     * })
     */
    private $ownerCompany;

    public function __construct()
    {
        $this->salt = bin2hex(openssl_random_pseudo_bytes(16));
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
     * Set name
     *
     * @param string $name
     * @return SystemUser
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
     * Set username
     *
     * @param string $username
     * @return SystemUser
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

    /**
     * Set password
     *
     * @param string $password
     * @return SystemUser
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
     * @return SystemUser
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
     * @return SystemUser
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
     * Set systemUserRole
     *
     * @param \AppBundle\Entity\SystemUserRole $systemUserRole
     * @return SystemUser
     */
    public function setSystemUserRole(\AppBundle\Entity\SystemUserRole $systemUserRole = null)
    {
        $this->systemUserRole = $systemUserRole;

        return $this;
    }

    /**
     * Get systemUserRole
     *
     * @return \AppBundle\Entity\SystemUserRole 
     */
    public function getSystemUserRole()
    {
        return $this->systemUserRole;
    }

    /**
     * Set ownerCompany
     *
     * @param \AppBundle\Entity\OwnerCompany $ownerCompany
     * @return SystemUser
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

    public function getRoles()
    {
        return array('ROLE_SYSTEM_USER_COMMON',$this->getSystemUserRole()->getCode());
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
//             $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
//             $this->salt
            ) = unserialize($serialized);
    }
}
