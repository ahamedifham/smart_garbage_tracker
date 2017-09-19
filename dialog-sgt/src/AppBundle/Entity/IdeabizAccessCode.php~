<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/9/16
 * Time: 5:59 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * IdeabizAccessCode
 *
 * @ORM\Table(name="ideabiz_access_code")
 * @ORM\Entity(repositoryClass="AppBundle\RepositoryORM\IdeabizAccessCode")
 */

class IdeabizAccessCode
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
     *
     * @ORM\Column(name="meta_code", type="string", length=20, nullable=false)
     */
    private $metaCode;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=500, nullable=false)
     */
    private $value;

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
     * Set metaCode
     *
     * @param string $metaCode
     * @return IdeabizAccessCode
     */
    public function setMetaCode($metaCode)
    {
        $this->metaCode = $metaCode;

        return $this;
    }

    /**
     * Get metaCode
     *
     * @return string 
     */
    public function getMetaCode()
    {
        return $this->metaCode;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return IdeabizAccessCode
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}
