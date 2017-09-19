<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemRoleAccessConfig
 *
 * @ORM\Table(name="system_role_access_config", indexes={@ORM\Index(name="fk_system_role_access_config_system_user_role1_idx", columns={"system_user_role_id"}), @ORM\Index(name="fk_system_role_access_config_system_user_access1_idx", columns={"system_user_access_id"})})
 * @ORM\Entity
 */
class SystemRoleAccessConfig
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
     * @var \SystemUserRole
     *
     * @ORM\ManyToOne(targetEntity="SystemUserRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="system_user_role_id", referencedColumnName="id")
     * })
     */
    private $systemUserRole;

    /**
     * @var \SystemUserAccess
     *
     * @ORM\ManyToOne(targetEntity="SystemUserAccess")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="system_user_access_id", referencedColumnName="id")
     * })
     */
    private $systemUserAccess;



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
     * @return SystemRoleAccessConfig
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
     * @return SystemRoleAccessConfig
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
     * Set systemUserAccess
     *
     * @param \AppBundle\Entity\SystemUserAccess $systemUserAccess
     * @return SystemRoleAccessConfig
     */
    public function setSystemUserAccess(\AppBundle\Entity\SystemUserAccess $systemUserAccess = null)
    {
        $this->systemUserAccess = $systemUserAccess;

        return $this;
    }

    /**
     * Get systemUserAccess
     *
     * @return \AppBundle\Entity\SystemUserAccess 
     */
    public function getSystemUserAccess()
    {
        return $this->systemUserAccess;
    }
}
