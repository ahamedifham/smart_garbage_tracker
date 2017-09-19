<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/21/16
 * Time: 10:38 AM
 */

namespace AppBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use AppBundle\Document\Coordinates;


/**
 * Class RouteInfo
 * @MongoDB\Document(collection="routeInfo", repositoryClass="AppBundle\RepositoryDOM\RouteInfoRepository")
 * @MongoDB\HasLifecycleCallbacks()
 */
class RouteInfo
{
    /**
     * @MongoDB\Id
     */
    protected $id;
    /**
     * @MongoDB\EmbedMany(targetDocument="Coordinates")
     */
    protected $path=[];
    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $isEdited;
    /**
     * @MongoDB\EmbedMany(targetDocument="Coordinates")
     */
    protected $resampledPath=[];
    /**
     * @MongoDB\EmbedMany(targetDocument="Coordinates")
     */
    protected $pinPoint;

    /**
     * @MongoDB\EmbedMany(targetDocument="Coordinates")
     */
    protected $dumpPoint;
    /**
     * @MongoDB\EmbedMany(targetDocument="Coordinates")
     */
    protected $recyclingPoint;
    /**
     * @MongoDB\EmbedMany(targetDocument="Coordinates")
     */
    protected $smartBinPoint;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $status;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $angleMin;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $distanceMin;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $angleDivisor;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $distanceDivisor;

    /**
     * @MongoDB\Field(type="collection")
     */
    protected $hashAngleArray;

    /**
     * @MongoDB\Field(type="collection")
     */
    protected $hashDistanceArray;

    /**
     * @MongoDB\Field(type="collection")
     */
    protected $accumulateEdge;


    /**
     * @MongoDB\Field(type="collection")
     */
    protected $nodes;



    public function __construct()
    {
        $this->path = new \Doctrine\Common\Collections\ArrayCollection();
        $this->resampledPath = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pinPoint = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dumpPoint= new \Doctrine\Common\Collections\ArrayCollection();
        $this->recyclingPoint= new \Doctrine\Common\Collections\ArrayCollection();
        $this->smartBinPoint= new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Add path
     *
     * @param Coordinates $path
     */
    public function addPath(Coordinates $path)
    {
        $this->path[] = $path;
    }

    /**
     * Remove path
     *
     * @param Coordinates $path
     */
    public function removePath(Coordinates $path)
    {
        $this->path->removeElement($path);
    }

    /**
     * Get path
     *
     * @return \Doctrine\Common\Collections\Collection $path
     */
    public function getPath()
    {
        return $this->path;
    }

    public function removeAllPath(){
        $this->path = new ArrayCollection();

    }

    /**
     * Set isEdited
     *
     * @param boolean $isEdited
     * @return self
     */
    public function setIsEdited($isEdited)
    {
        $this->isEdited = $isEdited;
        return $this;
    }

    /**
     * Get isEdited
     *
     * @return boolean $isEdited
     */
    public function getIsEdited()
    {
        return $this->isEdited;
    }

    /**
     * Add resampledPath
     *
     * @param Coordinates $resampledPath
     */
    public function addResampledPath(Coordinates $resampledPath)
    {
        $this->resampledPath[] = $resampledPath;
    }

    /**
     * Remove resampledPath
     *
     * @param Coordinates $resampledPath
     */
    public function removeResampledPath(Coordinates $resampledPath)
    {
        $this->resampledPath->removeElement($resampledPath);
    }

    /**
     * Get resampledPath
     *
     * @return \Doctrine\Common\Collections\Collection $resampledPath
     */
    public function getResampledPath()
    {
        return $this->resampledPath;
    }

    /**
     * Add pinPoint
     *
     * @param Coordinates $pinPoint
     */
    public function addPinPoint(\AppBundle\Document\Coordinates $pinPoint)
    {
        $this->pinPoint[] = $pinPoint;
    }

    /**
     * Remove pinPoint
     *
     * @param Coordinates $pinPoint
     */
    public function removePinPoint(\AppBundle\Document\Coordinates $pinPoint)
    {
        $this->pinPoint->removeElement($pinPoint);
    }

    /**
     * Get pinPoint
     *
     * @return \Doctrine\Common\Collections\Collection $pinPoint
     */
    public function getPinPoint()
    {
        return $this->pinPoint;
    }
    
    public function removeAllPinPoints(){
        $this->pinPoint = new ArrayCollection();
    }



    /**
     * Set status
     *
     * @param boolean $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add dumpPoint
     *
     * @param Coordinates $dumpPoint
     */
    public function addDumpPoint(\AppBundle\Document\Coordinates $dumpPoint)
    {
        $this->dumpPoint[] = $dumpPoint;
    }

    /**
     * Remove dumpPoint
     *
     * @param Coordinates $dumpPoint
     */
    public function removeDumpPoint(\AppBundle\Document\Coordinates $dumpPoint)
    {
        $this->dumpPoint->removeElement($dumpPoint);
    }

    /**
     * Get dumpPoint
     *
     * @return \Doctrine\Common\Collections\Collection $dumpPoint
     */
    public function getDumpPoint()
    {
        return $this->dumpPoint;
    }

    public function removeAllDumpPoints(){
        $this->dumpPoint = new ArrayCollection();
    }
    /**
     * Add recyclingPoint
     *
     * @param Coordinates $recyclingPoint
     */
    public function addRecyclingPoint(\AppBundle\Document\Coordinates $recyclingPoint)
    {
        $this->recyclingPoint[] = $recyclingPoint;
    }

    /**
     * Remove recyclingPoint
     *
     * @param Coordinates $recyclingPoint
     */
    public function removeRecyclingPoint(\AppBundle\Document\Coordinates $recyclingPoint)
    {
        $this->recyclingPoint->removeElement($recyclingPoint);
    }

    /**
     * Get recyclingPoint
     *
     * @return \Doctrine\Common\Collections\Collection $recyclingPoint
     */
    public function getRecyclingPoint()
    {
        return $this->recyclingPoint;
    }

    public function removeAllRecyclingPoints(){
        $this->recyclingPoint = new ArrayCollection();
    }

    /**
     * Add smartBinPoint
     *
     * @param Coordinates $smartBinPoint
     */
    public function addSmartBinPoint(\AppBundle\Document\Coordinates $smartBinPoint)
    {
        $this->smartBinPoint[] = $smartBinPoint;
    }

    /**
     * Remove smartBinPoint
     *
     * @param Coordinates $smartBinPoint
     */
    public function removeSmartBinPoint(\AppBundle\Document\Coordinates $smartBinPoint)
    {
        $this->smartBinPoint->removeElement($smartBinPoint);
    }

    /**
     * Get smartBinPoint
     *
     * @return \Doctrine\Common\Collections\Collection $smartBinPoint
     */
    public function getSmartBinPoint()
    {
        return $this->smartBinPoint;
    }

    public function removeAllSmartBinPoints(){
        $this->smartBinPoint= new ArrayCollection();
    }

    /**
     * Set angleMin
     *
     * @param float $angleMin
     * @return self
     */
    public function setAngleMin($angleMin)
    {
        $this->angleMin = $angleMin;
        return $this;
    }

    /**
     * Get angleMin
     *
     * @return float $angleMin
     */
    public function getAngleMin()
    {
        return $this->angleMin;
    }

    /**
     * Set distanceMin
     *
     * @param float $distanceMin
     * @return self
     */
    public function setDistanceMin($distanceMin)
    {
        $this->distanceMin = $distanceMin;
        return $this;
    }

    /**
     * Get distanceMin
     *
     * @return float $distanceMin
     */
    public function getDistanceMin()
    {
        return $this->distanceMin;
    }

    /**
     * Set hashAngleArray
     *
     * @param collection $hashAngleArray
     * @return self
     */
    public function setHashAngleArray($hashAngleArray)
    {
        $this->hashAngleArray = $hashAngleArray;
        return $this;
    }

    /**
     * Get hashAngleArray
     *
     * @return collection $hashAngleArray
     */
    public function getHashAngleArray()
    {
        return $this->hashAngleArray;
    }

    /**
     * Set hashDistanceArray
     *
     * @param collection $hashDistanceArray
     * @return self
     */
    public function setHashDistanceArray($hashDistanceArray)
    {
        $this->hashDistanceArray = $hashDistanceArray;
        return $this;
    }

    /**
     * Get hashDistanceArray
     *
     * @return collection $hashDistanceArray
     */
    public function getHashDistanceArray()
    {
        return $this->hashDistanceArray;
    }

    /**
     * Set accumulateEdge
     *
     * @param collection $accumulateEdge
     * @return self
     */
    public function setAccumulateEdge($accumulateEdge)
    {
        $this->accumulateEdge = $accumulateEdge;
        return $this;
    }

    /**
     * Get accumulateEdge
     *
     * @return collection $accumulateEdge
     */
    public function getAccumulateEdge()
    {
        return $this->accumulateEdge;
    }

    /**
     * Set nodes
     *
     * @param collection $nodes
     * @return self
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
        return $this;
    }

    /**
     * Get nodes
     *
     * @return collection $nodes
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    
    
    /**
     * Set angleDivisor
     *
     * @param string $angleDivisor
     * @return self
     */
    public function setAngleDivisor($angleDivisor)
    {
        $this->angleDivisor = $angleDivisor;
        return $this;
    }

    /**
     * Get angleDivisor
     *
     * @return string $angleDivisor
     */
    public function getAngleDivisor()
    {
        return $this->angleDivisor;
    }

    /**
     * Set distanceDivisor
     *
     * @param string $distanceDivisor
     * @return self
     */
    public function setDistanceDivisor($distanceDivisor)
    {
        $this->distanceDivisor = $distanceDivisor;
        return $this;
    }

    /**
     * Get distanceDivisor
     *
     * @return string $distanceDivisor
     */
    public function getDistanceDivisor()
    {
        return $this->distanceDivisor;
    }
}
