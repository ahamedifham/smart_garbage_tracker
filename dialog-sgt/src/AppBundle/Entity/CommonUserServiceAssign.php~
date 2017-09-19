<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommonUserServiceAssign
 *
 * @ORM\Table(name="common_user_service_assign", indexes={@ORM\Index(name="fk_common_user_service_assign_common_user_services1_idx", columns={"common_user_services_id"}), @ORM\Index(name="fk_common_user_service_assign_common_user1_idx", columns={"common_user_id"})})
 * @ORM\Entity
 */
class CommonUserServiceAssign
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
     * @var \CommonUserServices
     *
     * @ORM\ManyToOne(targetEntity="CommonUserServices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="common_user_services_id", referencedColumnName="id")
     * })
     */
    private $commonUserServices;

    /**
     * @var \CommonUser
     *
     * @ORM\ManyToOne(targetEntity="CommonUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="common_user_id", referencedColumnName="id")
     * })
     */
    private $commonUser;



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
     * Set commonUserServices
     *
     * @param \AppBundle\Entity\CommonUserServices $commonUserServices
     * @return CommonUserServiceAssign
     */
    public function setCommonUserServices(\AppBundle\Entity\CommonUserServices $commonUserServices = null)
    {
        $this->commonUserServices = $commonUserServices;

        return $this;
    }

    /**
     * Get commonUserServices
     *
     * @return \AppBundle\Entity\CommonUserServices 
     */
    public function getCommonUserServices()
    {
        return $this->commonUserServices;
    }

    /**
     * Set commonUser
     *
     * @param \AppBundle\Entity\CommonUser $commonUser
     * @return CommonUserServiceAssign
     */
    public function setCommonUser(\AppBundle\Entity\CommonUser $commonUser = null)
    {
        $this->commonUser = $commonUser;

        return $this;
    }

    /**
     * Get commonUser
     *
     * @return \AppBundle\Entity\CommonUser 
     */
    public function getCommonUser()
    {
        return $this->commonUser;
    }
}
