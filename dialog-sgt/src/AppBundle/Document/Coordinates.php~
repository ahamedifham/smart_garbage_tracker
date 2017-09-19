<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/21/16
 * Time: 10:09 AM
 */

namespace AppBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


/**
 * Class Coordinates
 * @MongoDB\Document()
 * @MongoDB\EmbeddedDocument()
 * @MongoDB\HasLifecycleCallbacks()
 */
class Coordinates
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $lat;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $lng;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return self
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * Get lat
     *
     * @return float $lat
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return self
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * Get lng
     *
     * @return float $lng
     */
    public function getLng()
    {
        return $this->lng;
    }
}
