<?php
/**
 * Created by PhpStorm.
 * User: Janith
 * Date: 11/14/2016
 * Time: 10:36 PM
 */
namespace AppBundle\AlertAlgorithm;

use AppBundle\geolocation\LatLng;
use AppBundle\geolocation;
use AppBundle\geolocation\SphericalGeometry;
use Doctrine\DBAL\VersionAwarePlatformDriver;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Null;

include 'spherical-geometry.class.php';
//$GLOBALS= array(
//
//    'routeNodes'=>array(array()),
//    'routeAngleMin'=>array(array()),
//    'routeDistanceMin'=>array(array()),
//    'routeAccumulateEdge'=>array(array()),
//    'routeHashAngleArray'=>array(array(array())),
//    'routeHashDistanceArray'=>array(array(array())),
//    'routeAngleDivisor'=>array(),
//    'routeDistanceDivisor'=>array(),
//    'accumulateEdge'=>array(array())
//);
class Algorithm
{

//    protected $glob;
//
//    public function __construct() {
//        global $GLOBALS;
//        $this->glob =& $GLOBALS;
//    }
//
//    public function getGlob() {
//        return $this->glob;
//    }
    private $doctrineORM;
    private $doctrineODM;
    private $redis;

    public function __construct($doctrineORM,$doctrineODM,$redis)
    {
        $this->doctrineORM = $doctrineORM;
        $this->doctrineODM = $doctrineODM;
        $this->redis = $redis;
    }

    const OriginLat=5.8;
    const OriginLng=78;
    const MaxEdgeSize=10; //5m
    const AnglePartitionNo=100000;
    const DistancePartitionNo=100000;
    const ErrorLimit=5000; //20m


    public static function LoadRoute($mainNodes,$routeId)
    {
        //var_dump('came 2.1');
        $accumulateEdge=array();
        $nodes=array();
        $origin= new LatLng(self::OriginLat,self::OriginLng);
        $accumulateEdgeTemp=0;
        $countTemp=0;
        //var_dump('came 2.2');
        for ($count=0;$count<count($mainNodes)-1;$count++){

            $tempDistance= SphericalGeometry::computeDistanceBetween($mainNodes[$count],$mainNodes[$count+1]);
            $tempAngle = SphericalGeometry::computeHeading($mainNodes[$count],$mainNodes[$count]);

            $nodes[$countTemp++]=$mainNodes[$count];
            $accumulateEdge[$countTemp-1]=$accumulateEdgeTemp;
            for ($i=1;$i<$tempDistance/(self::MaxEdgeSize);$i++){
                $nodes[$countTemp++]=SphericalGeometry::computeOffset($mainNodes[$count],$i*self::MaxEdgeSize,$tempAngle);
                $accumulateEdgeTemp+=self::MaxEdgeSize;
                $accumulateEdge[$countTemp-1]=$accumulateEdgeTemp;
            }
            $accumulateEdgeTemp+=($tempDistance-(($i-1)*self::MaxEdgeSize));
        }
        //var_dump('came 2.3');
        $totalNode=$countTemp;
        $distanceMax=0;
        $distanceMin=10000000;
        $angleMax=-180;
        $angleMin=180;

        $distance=array_fill(0,$totalNode,0);
        $angle=array_fill(0,$totalNode,0);
        //var_dump('came 2.4');
        for ($count=0;$count<$totalNode;$count++) {

            $distance[$count]=SphericalGeometry::computeDistanceBetween($origin,$nodes[$count]);
            if($distance[$count]<=$distanceMin) $distanceMin=$distance[$count];
            if($distance[$count]>$distanceMax) $distanceMax=$distance[$count];

            $angle[$count]=SphericalGeometry::computeHeading($origin,$nodes[$count]);
            if($angle[$count]<=$angleMin) $angleMin=$angle[$count];
            if($angle[$count]>$angleMax) $angleMax=$angle[$count];


        }
        //var_dump('came 2.5');
        $hashAngleArray=array_fill(0,self::AnglePartitionNo,null);
        $hashDistanceArray=array_fill(0,self::DistancePartitionNo,null);

        $hashAngleCount=array_fill(0,self::AnglePartitionNo+1,0);
        $hashDistanceCount=array_fill(0,self::DistancePartitionNo+1,0);

        $angleDivisor=($angleMax-$angleMin)/self::AnglePartitionNo;
        $distanceDivisor=($distanceMax-$distanceMin)/self::DistancePartitionNo;

        for ($count=0;$count<$totalNode;$count++) {
            $n = intval(($angle[$count] - $angleMin) / $angleDivisor);
            $m = intval(($distance[$count] - $distanceMin) / $distanceDivisor);
            $hashAngleArray[$n][$hashAngleCount[$n]++] = $count;
            $hashDistanceArray[$m][$hashDistanceCount[$m]++] = $count;
        }
        //var_dump('came 2.6');


        // var_dump($hashAngleArray);

//        $GLOBALS['routeNodes'][$routeId]=$nodes;
//        $GLOBALS['routeAngleMin'][$routeId]=$angleMin;
//        $GLOBALS['routeDistanceMin'][$routeId]=$distanceMin;
//        $GLOBALS['routeHashAngleArray'][$routeId]=$hashAngleArray;
//        $GLOBALS['routeHashDistanceArray'][$routeId]=$hashDistanceArray;
//        $GLOBALS['routeAngleDivisor'][$routeId]=$angleDivisor;
//        $GLOBALS['routeDistanceDivisor'][$routeId]=$distanceDivisor;
//        $GLOBALS['accumulateEdge'][$routeId]=$accumulateEdge;

        return array($nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor, $distanceDivisor,$accumulateEdge);

    }
    public static function SearchLatLng($routeId,$lat,$lng,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge)
    {
        $LatLng=new LatLng($lat,$lng);
        $origin= new LatLng(self::OriginLat,self::OriginLng);



//        $nodes=$GLOBALS['routeNodes'][$routeId];
//        $angleMin=$GLOBALS['routeAngleMin'][$routeId];
//        $distanceMin=$GLOBALS['routeDistanceMin'][$routeId];
//        $hashAngleArray=$GLOBALS['routeHashAngleArray'][$routeId];
//        $hashDistanceArray=$GLOBALS['routeHashDistanceArray'][$routeId];
//        $angleDivisor=$GLOBALS['routeAngleDivisor'][$routeId];
//        $distanceDivisor=$GLOBALS['routeDistanceDivisor'][$routeId];



        $distance=SphericalGeometry::computeDistanceBetween($origin,$LatLng);
        $angle=SphericalGeometry::computeHeading($origin,$LatLng);

        $nodeIs=null;

        $angleVal=intval(($angle-$angleMin)/$angleDivisor);
        $distanceVal=intval(($distance-$distanceMin)/$distanceDivisor);

        $errorAngleOffset=intval(self::ErrorLimit/(2*$distance*$angleDivisor)+1);
        $errorDistanceOffset=intval(self::ErrorLimit/(2*$distanceDivisor)+1);

        $decrement1=0;
        $angleIds=array();
        if($angleVal<0)$angleVal=0;
        elseif($angleVal>self::AnglePartitionNo-$errorAngleOffset)$angleVal=self::AnglePartitionNo-$errorAngleOffset;
        while($decrement1<=2*$errorAngleOffset){
            $index=$angleVal-$decrement1+$errorAngleOffset;
            if($index<0){
                echo "there is no route\n";
                break;
            }
            if($hashAngleArray[$index]!=null) {
                $angleIds = array_merge($angleIds, $hashAngleArray[$index]);
            }
            $decrement1+=1;
        }
        $decrement2=0;
        $distanceIds=array();
        if($distanceVal<0)$distanceVal=0;
        elseif($distanceVal>self::DistancePartitionNo-$errorDistanceOffset)$distanceVal=self::DistancePartitionNo-$errorDistanceOffset;

        while($decrement2<=2*$errorDistanceOffset){
            $index=$distanceVal-$decrement2+$errorDistanceOffset;
            if($index<0){
                echo "there is no route 2\n";
                break;
            }
            if(sizeof($hashDistanceArray)>$index) {
                if ($hashDistanceArray[$index] != null) {
                    $distanceIds = array_merge($distanceIds, $hashDistanceArray[$index]);
                }
            }
            $decrement2+=1;
        }

        $tempMinNodeDistance=100000;
        foreach ($angleIds as $angleId){
            $distanceTemp = SphericalGeometry::computeDistanceBetween($nodes[$angleId], $LatLng);
            if (($tempMinNodeDistance > $distanceTemp)&&($distanceTemp<self::ErrorLimit)) {
                //echo("distanceToNearNode:".$distanceTemp . "\n");
                $tempMinNodeDistance = $distanceTemp;
                $nodeIs = $angleId;
            }
        }
        foreach ($distanceIds as $distanceId){
            //var_dump($distanceId);
            // var_dump(sizeof($nodes));

            if(sizeof($nodes)>$distanceId){
                //var_dump('Boom');
                // exit();
                $distanceTemp = SphericalGeometry::computeDistanceBetween($nodes[$distanceId], $LatLng);
            }
            // var_dump(self::ErrorLimit , $distanceTemp);

            if (($tempMinNodeDistance > $distanceTemp)&&($distanceTemp<self::ErrorLimit)) {
                // echo("distanceToNearNode:".$distanceTemp . "\n");
                $tempMinNodeDistance = $distanceTemp;
                $nodeIs = $distanceId;
            }
        }
        if ($nodeIs==null){
            echo ("can not find a node\n");

        }
        else {
            return $nodeIs;
        }
    }
    public static function getTimeToDumpPoints($routeId,$lat,$lng,$speed,$dumpPointIds,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge)
    {

        //$accumulateEdge=$GLOBALS['accumulateEdge'][$routeId];
        $timeToDumpPoint=array();
        $currentNodeId=self::SearchLatLng($routeId,$lat,$lng,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);
        //var_dump($currentNodeId);
//exit();
        if($currentNodeId==null){
            return [0];
        }
        foreach ($dumpPointIds as $index=>$dumpPointId)
        {
            if($dumpPointId!=null){
                $timeToDumpPoint[$index]=($accumulateEdge[$dumpPointId]-$accumulateEdge[$currentNodeId])/$speed;
            }else{
                $timeToDumpPoint= [0];
            }
        }
        return $timeToDumpPoint;

    }

    /**
     * @param $trackId
     * @param $newLat
     * @param $newLng
     * @param $timeStamp
     * @return float
     */
    //returns the predicted speed of the truck by analyzing the previous location logs of the truck
    public function getPredictedSpeed($trackId,$newLat,$newLng,$timeStamp,$serializer){

        /**
         * only comes to this function only if the truck is in schedule
         */
        $em     = $this->doctrineORM;
        $redis  = $this->redis;

        //with each log the trucks' assigned node is taken
        //distance travelled between current and previous node can be obtained

        //by calculating the distance between previous node and current node
        //speed  can be calculated

        //all these speed samples are weighted according to time
        //i.e. most recent speed has the highest weight

        $previousSpeed      = $redis->get('trackUnit'.$trackId.':previousSpeed');
        $previousDistance   = $redis->get('trackUnit'.$trackId.':previousDistance');
        $previousTime       = $redis->get('trackUnit'.$trackId.':previousTime');
        $routeId            = $redis->get('trackUnit'.$trackId.':routeId');
        $isInSchedule       = $redis->get('trackUnit'.$trackId.':isInSchedule');
        //if tracking is not already initialized, initialize


        var_dump($previousSpeed, $previousDistance, $previousTime, $routeId, $isInSchedule);

        if (is_null($isInSchedule)|| $isInSchedule==false || is_null($routeId)){
//            $previousSpeed=0;
            $previousDistance=0;        //in metres
            $previousTime=0;            //in seconds
            if (is_null($routeId)) {
                $trackingUnitRepo   = $em->getRepository('AppBundle:TrackingUnit');
                $trackingUnit       = $trackingUnitRepo ->find($trackId);
                $routeId            = $trackingUnit     ->getRoute()->getId();

                $redis->set('trackUnit' . $trackId . ':routeId', $routeId);
            }
            $redis->set('trackUnit'.$trackId.':isInSchedule',true);

        }


        $rawNodes               = $redis->get('route'.$routeId.':nodes');
        $rawAngleMin            = $redis->get('route'.$routeId.':angleMin');
        $rawDistanceMin         = $redis->get('route'.$routeId.':distanceMin');
        $rawHashAngleArray      = $redis->get('route'.$routeId.':hashAngleArray');
        $rawHashDistanceArray   = $redis->get('route'.$routeId.':hashDistanceArray');
        $rawAngleDivisor        = $redis->get('route'.$routeId.':angleDivisor');
        $rawDistanceDivisor     = $redis->get('route'.$routeId.':distanceDivisor');
        $rawAccumulateEdge      = $redis->get('route'.$routeId.':accumulateEdge');



        $nodes=$serializer->deserialize($rawNodes,'AppBundle\geolocation\LatLng[]','json');

        $angleMin           = json_decode($rawAngleMin);
        $distanceMin        = json_decode($rawDistanceMin);
        $hashAngleArray     = json_decode($rawHashAngleArray);
        $hashDistanceArray  = json_decode($rawHashDistanceArray);
        $angleDivisor       = json_decode($rawAngleDivisor);
        $distanceDivisor    = json_decode($rawDistanceDivisor);
        $accumulateEdge     = json_decode($rawAccumulateEdge);




        $nodeIndex = Algorithm::SearchLatLng($routeId,$newLat,$newLng,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);


        var_dump('Node Index is');
        var_dump($nodeIndex);

        var_dump('Accumulate Node index is ');
        //var_dump($accumulateEdge[$nodeIndex]);
        var_dump('Boom');

        if ($nodeIndex == Null){
            $newDistanceTravelled = $accumulateEdge[sizeof($accumulateEdge)-1]-$previousDistance;
        }else{
            $newDistanceTravelled=$accumulateEdge[$nodeIndex]-$previousDistance;
        }



        $newTime=strtotime($timeStamp);
//        var_dump($newDistanceTravelled);
//        var_dump($newTime);
//        var_dump($previousTime);
        if ($newTime==$previousTime || $previousTime==0)    $newSpeed=30;       //kmph default speed
        else    $newSpeed = $newDistanceTravelled / (($newTime - $previousTime));           //in ms-1

        if($previousSpeed==0 || is_null($previousSpeed))    $previousSpeed=$newSpeed;

        //
        $newPredictedSpeed=($newSpeed+$previousSpeed)/2; //speed in ms-1


        $previousSpeed      = $newPredictedSpeed;
        //$previousDistance   = $accumulateEdge[$nodeIndex];
        if ($nodeIndex == Null){
            $previousDistance = $accumulateEdge[sizeof($accumulateEdge)-1];
        }else{
            $previousDistance   = $accumulateEdge[$nodeIndex];
        }

        $previousTime       = $newTime;

        $redis->set('trackUnit'.$trackId.':previousSpeed',      $previousSpeed);
        $redis->set('trackUnit'.$trackId.':previousDistance',   $previousDistance);
        $redis->set('trackUnit'.$trackId.':previousTime',       $previousTime);

//        var_dump($newPredictedSpeed);exit;

        //hence speed prediction is returned
        return $newPredictedSpeed*3.6;      //return in kmph
        //return $newPredictedSpeed;      //return in ms-1

    }
}
?>