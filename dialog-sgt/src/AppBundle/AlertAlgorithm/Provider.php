<?php
/**
 * Created by PhpStorm.
 * User: umayanga
 * Date: 1/10/17
 * Time: 11:17 AM
 */

namespace AppBundle\AlertAlgorithm;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class Provider
{
    private $doctrineORM;
    private $doctrineODM;
    private $redis;

    private $userNodeAssign;
    private $serializer;
    public function __construct($doctrineORM,$doctrineODM,$redis)
    {
        $this->doctrineORM = $doctrineORM;
        $this->doctrineODM = $doctrineODM;
        $this->redis = $redis;

        $this->userNodeAssign = new UserNodeAssign($doctrineORM,$doctrineODM,$redis);
        $this->algorithm = new Algorithm($doctrineORM,$doctrineODM,$redis);

        $this->serializer = new Serializer(
            array(new GetSetMethodNormalizer(), new ArrayDenormalizer()),
            array(new JsonEncoder())
        ); //TODO this needs to be more generalized than this and more compact

    }

    /**
     * @param $mainNodes
     * @param $routeId
     * @return array
     */
    public function loadRoute($mainNodes,$routeId){
        return Algorithm::LoadRoute($mainNodes,$routeId);
    }

    public function snapToNode($routeId,$lat,$lng,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge)
    {
        return Algorithm::SearchLatLng($routeId,$lat,$lng,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);
    }

    public function UpdateNodeStruct(\AppBundle\Entity\CommonUser $newUser,$serializer)
    {
        return $this->userNodeAssign->UpdateNodeStruct($newUser,$serializer);
    }

    public function getPredictedSpeed($trackId,$newLat,$newLng,$timeStamp){
        return $this->algorithm->getPredictedSpeed($trackId,$newLat,$newLng,$timeStamp,$this->serializer);
    }

    public function getNearbyUsers ($lat,$lng,$routeId,$truckSpeed,$timeRange){
        return $this->userNodeAssign->getNearbyUsers ($lat,$lng,$routeId,$truckSpeed,$timeRange,$this->serializer);
    }

    public function getTimeFromRedisTable ($userId){
        return $this->userNodeAssign->getTimeFromRedisTable ($userId);
    }

}