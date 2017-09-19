<?php
/**
 * Created by PhpStorm.
 * User: umayanga
 * Date: 1/1/17
 * Time: 12:02 PM
 */
namespace AppBundle\AlertAlgorithm;

use AppBundle\Entity\CommonUser;
use AppBundle\geolocation\LatLng;
use AppBundle\geolocation;
use AppBundle\geolocation\SphericalGeometry;
use AppBundle\AlertAlgorithm\Algorithm;


class UserNodeAssign{

    private $doctrineORM;
    private $doctrineODM;
    private $redis;

    public function __construct($doctrineORM,$doctrineODM,$redis)
    {
        $this->doctrineORM  = $doctrineORM;
        $this->doctrineODM  = $doctrineODM;
        $this->redis        = $redis;
    }

    //add the new user to the route and return user distribution throughout the nodes
    //with accumulated distances
    /**
     * @param CommonUser $newUser
     * @param $serializer
     * @return mixed
     */
    public function UpdateNodeStruct(\AppBundle\Entity\CommonUser $newUser,$serializer){

        $em     = $this     ->doctrineORM;
        $redis  = $this     ->redis;
        $routeId= $newUser  ->getRoute()    ->getId();

        $rawNodes               = $redis->get('route'.$routeId.':nodes');
        $rawAngleMin            = $redis->get('route'.$routeId.':angleMin');
        $rawDistanceMin         = $redis->get('route'.$routeId.':distanceMin');
        $rawHashAngleArray      = $redis->get('route'.$routeId.':hashAngleArray');
        $rawHashDistanceArray   = $redis->get('route'.$routeId.':hashDistanceArray');
        $rawAngleDivisor        = $redis->get('route'.$routeId.':angleDivisor');
        $rawDistanceDivisor     = $redis->get('route'.$routeId.':distanceDivisor');
        $rawAccumulateEdge      = $redis->get('route'.$routeId.':accumulateEdge');
        $rawUsers               = $redis->get('route'.$routeId.':users');

        $nodes              = $serializer->deserialize($rawNodes,'AppBundle\geolocation\LatLng[]','json');
        $angleMin           = json_decode($rawAngleMin);
        $distanceMin        = json_decode($rawDistanceMin);
        $hashAngleArray     = json_decode($rawHashAngleArray);
        $hashDistanceArray  = json_decode($rawHashDistanceArray);
        $angleDivisor       = json_decode($rawAngleDivisor);
        $distanceDivisor    = json_decode($rawDistanceDivisor);
        $accumulateEdge     = json_decode($rawAccumulateEdge);
        $usersList          = json_decode($rawUsers);

//        $usersList=$serializer->deserialize($rawUsers,'AppBundle\Entity\CommonUser[]','json');

        //assign collect point to new user
        $tempIndex = Algorithm::SearchLatLng($routeId,$newUser->getCollectPointLat(),$newUser->getCollectPointLng(),$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);
        $newUser->setCollectPointHashId($tempIndex);
        $em->flush();

        //encode new user
        $userId=$newUser->getId();

        //check if user is already in the list
        $userFound=false;
        foreach ($usersList as $user){
            if ($user->id==$userId){

                $user       =$newUser;
                $userFound  =true;
            }
        }

        //if not, add as a new user to the array
        if ($userFound==false)  $usersList[]=$newUser;


        //load new struct to redis
        $jsonUsers  =json_encode($usersList);
//        $jsonUsers = $serializer->serialize($usersList, 'json');
        $redis->set('route'.$routeId.':users',$jsonUsers);

        return $usersList;  //returns as stdClass object array
    }

    //given the location of the truck, time range & route nodes
    ///returns the nearby users in the route to be alerted
    /**
     * @param $lat
     * @param $lng
     * @param $routeId
     * @param $truckSpeed
     * @param $timeRange
     * @param $serializer
     * @return array
     */
    public function getNearbyUsers ($lat,$lng,$routeId,$truckSpeed,$timeRange,$serializer){

//        $em = $this->doctrineORM;
        $redis = $this->redis;

//        $routeRepo= $em->getRepository('AppBundle:Route');
//        $commonUserRepo= $em->getRepository('AppBundle:CommonUser');

        $rawNodes               = $redis->get('route'.$routeId.':nodes');
        $rawAngleMin            = $redis->get('route'.$routeId.':angleMin');
        $rawDistanceMin         = $redis->get('route'.$routeId.':distanceMin');
        $rawHashAngleArray      = $redis->get('route'.$routeId.':hashAngleArray');
        $rawHashDistanceArray   = $redis->get('route'.$routeId.':hashDistanceArray');
        $rawAngleDivisor        = $redis->get('route'.$routeId.':angleDivisor');
        $rawDistanceDivisor     = $redis->get('route'.$routeId.':distanceDivisor');
        $rawAccumulateEdge      = $redis->get('route'.$routeId.':accumulateEdge');
        $rawUsers               = $redis->get('route'.$routeId.':users');

        $nodes              = $serializer->deserialize($rawNodes,'AppBundle\geolocation\LatLng[]','json');
        $angleMin           = json_decode($rawAngleMin);
        $distanceMin        = json_decode($rawDistanceMin);
        $hashAngleArray     = json_decode($rawHashAngleArray);
        $hashDistanceArray  = json_decode($rawHashDistanceArray);
        $angleDivisor       = json_decode($rawAngleDivisor);
        $distanceDivisor    = json_decode($rawDistanceDivisor);
        $accumulateEdge     = json_decode($rawAccumulateEdge);
        $users              = json_decode($rawUsers);

        //get route
//        $route = $routeRepo->profile($routeId);

        //get the truck assigned nodeId
        $truckNodeIndex = Algorithm::SearchLatLng($routeId,$lat,$lng,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);

        var_dump('Truck Node index is');
        var_dump($truckNodeIndex);

        var_dump('size of Users are');
        var_dump(sizeof($users));

        // get all users in the truck route ahead of the truck
        $usersForGivenRouteAhead    = array();
        $collectionPointIds         = array();

        // select users ahead of the truck and assign those users to resampled nodes
        foreach ($users as $user){
//            $tempUserLat = $user->getCollectPointLat();
//            $tempUserLng = $user->getCollectPointLng();
//
//            $tempIndex = Algorithm::SearchLatLng($routeId,$tempUserLat,$tempUserLng,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);

            //COmmented by ifham for debugging
//            $collectPointId =$user->collectPointHashId;
//            if ($collectPointId>$truckNodeIndex){
//                $usersForGivenRouteAhead[]  =$user;
//                $collectionPointIds[]       = $collectPointId;
//            }

            $usersForGivenRouteAhead[]  =$user;
            $collectPointId =$user->collectPointHashId;

            $collectionPointIds[]       = $collectPointId;


        }

        //get times to reach for each user node
        $timetoCollectPoints=Algorithm::getTimeToDumpPoints($routeId,$lat,$lng,$truckSpeed,$collectionPointIds,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);

        //return users with arrive time less than time range in mins

        $userCount=sizeof($timetoCollectPoints);

        var_dump('time to collection points count');
        var_dump($userCount);

        $nearByUsers    = array();

        var_dump('TIme to collection points');
        var_dump($timetoCollectPoints);

        var_dump('Near by users');

        //commented by fham for debugging
//        for ($i=0;  $i<$userCount;  $i++) {
//            //if reach time is less than time range
//
//            var_dump($timetoCollectPoints[$i] < $timeRange);
//            if ($timetoCollectPoints[$i] < $timeRange)  $nearByUsers[]=$usersForGivenRouteAhead[$i];
//        }
        for ($i=0;  $i<$userCount;  $i++) {
            //if reach time is less than time range

            //save on redis the time calculated so that it can be sent to app

            var_dump('User id is');
            var_dump($usersForGivenRouteAhead[$i]->id);

            $redis->set('user'.$usersForGivenRouteAhead[$i]->id.':time',            $timetoCollectPoints[$i]);


            var_dump($timetoCollectPoints[$i] < $timeRange);

            if ($timetoCollectPoints[$i] < $timeRange && $timetoCollectPoints[$i] >0){
                $nearByUsers[]=$usersForGivenRouteAhead[$i];
            }

        }
        var_dump('Size of nearby users');
        var_dump(sizeof($nearByUsers));

//        var_dump($nearByUsers);
//        exit;
        return $nearByUsers;        //returned as a StdClass array
    }

    public function getTimeFromRedisTable($userId){
        $redis = $this->redis;
        $timeFromRedisTable = $redis->get('user'.$userId.':time');
        return $timeFromRedisTable;
    }

}