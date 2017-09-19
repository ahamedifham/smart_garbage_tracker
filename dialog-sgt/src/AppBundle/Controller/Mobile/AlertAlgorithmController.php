<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/18/16
 * Time: 10:45 AM
 */

namespace AppBundle\Controller\Mobile;
use AppBundle\AlertAlgorithm\UserNodeAssign;
use AppBundle\Entity\CommonUser;
use AppBundle\RepositoryORM\Base;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\geolocation;
use AppBundle\geolocation\SphericalGeometry;
use AppBundle\geolocation\LatLng;
use AppBundle\AlertAlgorithm\Algorithm;



class AlertAlgorithmController extends APIBaseController
{
    /**
     * @Route("/algorithm/test", name="app_algorithm_test")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Request $request){
        $mainNodes = array();
        $em = $this->getDoctrine()->getManager();
        $routeRepo= $em->getRepository('AppBundle:Route');
        $count = $routeRepo->count();
        $route = $routeRepo->profile(1);
        $routeId = $route->getRouteId();

        $pathPoints = $this->get('app.mongodb_dump_points')->path($routeId);

        for($i=0;$i<sizeof($pathPoints);$i++){
            $tempLat = $pathPoints[$i][0];
            $tempLng = $pathPoints[$i][1];
            $tempLatLng = new LatLng(floatval($tempLat), floatval($tempLng));
            $mainNodes[] = $tempLatLng;
        }

        Algorithm::LoadRoute($mainNodes,0);

        $testLat =  6.927478;
        $testLng = 79.99762;

        $testOutput= Algorithm::SearchLatLng(0, $testLat, $testLng);

        $dumpPoints = $this->get('app.mongodb_dump_points')->dumpPoints($routeId);

        $dumPointIds = array();

        for($j=0; $j<sizeof($dumpPoints);$j++){
            $tempDumpLat = $dumpPoints[$j][0];
            $tempDumpLng = $dumpPoints[$j][1];

            $tempIndex = Algorithm::SearchLatLng(0,floatval($tempDumpLat),floatval($tempDumpLng));
            $dumPointIds[] = $tempIndex;
        }

        var_dump($dumPointIds);

        $timeToPoint = Algorithm::getTimeToDumpPoints(0,floatval($dumpPoints[4][0]),floatval($dumpPoints[4][1]),10,$dumPointIds);

        var_dump($timeToPoint);

        return $this->handleResponse(0);
    }
    /**
     * @Route("/algorithm/load", name="app_algorithm_load")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loadAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $routeRepo= $em->getRepository('AppBundle:Route');
        $commonUserRepo= $em->getRepository('AppBundle:CommonUser');
        $count = $routeRepo->count();

        $dm = $this->get('doctrine_mongodb')->getManager();


        for($i=1; $i<$count+1;$i++){
            $route = $routeRepo->profile($i);
            $routeId = $route->getRouteId();
            //var_dump($routeId);
            $pathPoints = $this->get('app.mongodb_dump_points')->path($routeId);
            //var_dump('Size of path points is '.sizeof($pathPoints));
            $routeIntId = $route->getId();
            $mainNodes = array();

            for($j=0;$j<sizeof($pathPoints);$j++){
                $tempLat = $pathPoints[$j][0];
                $tempLng = $pathPoints[$j][1];
                $tempLatLng = new LatLng(floatval($tempLat), floatval($tempLng));
                $mainNodes[] = $tempLatLng;
            }


            $returnedValues = Algorithm::LoadRoute($mainNodes,$routeIntId);


            $nodes = $returnedValues[0];
            $angleMin = $returnedValues[1];
            $distanceMin = $returnedValues[2];
            $hashAngleArray = $returnedValues[3];
            $hashDistanceArray = $returnedValues[4];
            $angleDivisor = $returnedValues[5];
            $distanceDivisor = $returnedValues[6];
            $accumulateEdge = $returnedValues[7];

            $this->get('app.mongodb_dump_points')->setNodes($routeId, $nodes);
            $this->get('app.mongodb_dump_points')->setAngleMin($routeId, $angleMin);
            $this->get('app.mongodb_dump_points')->setDistanceMin($routeId, $distanceMin);
            $this->get('app.mongodb_dump_points')->setHashAngleArray($routeId, $hashAngleArray);
            $this->get('app.mongodb_dump_points')->setHashDistanceArray($routeId, $hashDistanceArray);
            $this->get('app.mongodb_dump_points')->setAngleDivisor($routeId, strval($angleDivisor));
            $this->get('app.mongodb_dump_points')->setDistanceDivisor($routeId, strval($distanceDivisor));
            $this->get('app.mongodb_dump_points')->setAccumulateEdge($routeId, $accumulateEdge);

//            $nodesToSend= array();
//
//            foreach ($nodes as $node){
//                $tempLat = $node->getLat();
//                $tempLng = $node->getLng();
//                $tempArray = [$tempLat, $tempLng];
//                $nodesToSend[] = $tempArray;
//            }




//            $routeInfo = $dm->getRepository('AppBundle:RouteInfo')->find($routeId);
//
//            $routeInfo->setHashAngleArray($hashAngleArray);

            //$routeInfo->setHashDistanceArray($nodesToSend);

//            $dm->flush();

          //  $routeInfo1 = $dm->getRepository('AppBundle:RouteInfo')->find($routeId);

//            $nodesGet = $routeInfo1->getHashDistanceArray();
//
//            $nodesGetLatLng = array();
//            foreach ($nodesGet as $item) {
//                $tempLatLng = new LatLng($item[0], $item[1]);
//                $nodesGetLatLng[] = $tempLatLng;
//            }



//            $nodesGet = $this->get('app.mongodb_dump_points')->getNodes($routeId);
//            $angleMinGet = $this->get('app.mongodb_dump_points')->getAngleMin($routeId);
//            $distanceMinGet = $this->get('app.mongodb_dump_points')->getDistanceMin($routeId);
//            $hashAngleArrayGet = $this->get('app.mongodb_dump_points')->getHashAngleArray($routeId);
//            $hashDistanceArrayGet = $this->get('app.mongodb_dump_points')->getHashDistanceArray($routeId);
//            $angleDivisorGet = $this->get('app.mongodb_dump_points')->getAngleDivisor($routeId);
//            $distanceDivisorGet = $this->get('app.mongodb_dump_points')->getDistanceDivisor($routeId);
//            $accumulateEdgeGet = $this->get('app.mongodb_dump_points')->getAccumulateEdge($routeId);
//
//            var_dump($angleDivisorGet);
//            var_dump($distanceDivisorGet);

//            var_dump($nodesGet==$nodes);
//            var_dump($angleMinGet==$angleMin);
//            var_dump($distanceMinGet==$distanceMin);
//            var_dump($hashAngleArrayGet==$hashAngleArray);
//            var_dump($hashDistanceArrayGet==$hashDistanceArray);
//            var_dump($angleDivisorGet==$angleDivisor);
//            var_dump($distanceDivisorGet==$distanceDivisor);
//            var_dump($accumulateEdgeGet==$accumulateEdge);

//            exit();



            $usersForGivenRoute = $commonUserRepo->getUsersForGivenRoute($route);
            $latAndLngsForUsersInGivenRoute = array();

            foreach ($usersForGivenRoute as $user){
                $userTempLat = $user->getCollectPointLat();
                $userTempLng = $user->getCollectPointLng();
                $userId = $user->getId();
                //var_dump('User Id is '.$user->getId());
                $userTempLatAndLng = array();
                $userTempLatAndLng[] = $userTempLat;
                $userTempLatAndLng[] = $userTempLng;
                $userTempLatAndLng[] = $userId;

                $latAndLngsForUsersInGivenRoute[] = $userTempLatAndLng;
            }

            $collectionPointIds = array();
            foreach ($latAndLngsForUsersInGivenRoute as $temLatAndLng){
                $tempUserLat = $temLatAndLng[0];
                $tempUserLng = $temLatAndLng[1];
                $tempIndex = Algorithm::SearchLatLng($routeIntId,$tempUserLat,$tempUserLng,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);
                //var_dump('Temo index is '.$tempIndex);
                $userFromId = $commonUserRepo->profile($temLatAndLng[2]);
                $userFromId->setCollectPointHashId($tempIndex);
                $userFromId->setNotificationSent(false);
                $collectionPointIds[] = $tempIndex;
            }

           // var_dump($collectionPointIds);

            //var_dump($latAndLngsForUsersInGivenRoute);

//            $dumpPoints = $this->get('app.mongodb_dump_points')->dumpPoints($routeId);
//            var_dump($dumpPoints);
//            $dumPointIds = array();
//            for($j=0; $j<sizeof($dumpPoints);$j++){
//                $tempDumpLat = $dumpPoints[$j][0];
//                $tempDumpLng = $dumpPoints[$j][1];
//
//               // var_dump($tempDumpLat, $tempDumpLng);
//
//                $tempIndex = Algorithm::SearchLatLng($routeIntId,floatval($tempDumpLat),floatval($tempDumpLng),$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);
//                $dumPointIds[] = $tempIndex;
//            }

            $em->flush();

            //exit();


            //$timeToDumpPoint  = Algorithm::getTimeToDumpPoints(1,6.9114732588491,80.00221811235,20,$dumPointIds,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);
           // var_dump($timeToDumpPoint);

            //var_dump($dumPointIds);

        }

        return $this->handleResponse(0);
    }

    /**
     * @Route("/algorithm/load_time", name="app_algorithm_load_time")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    
    public function loadTimeAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $routeRepo= $em->getRepository('AppBundle:Route');
        $commonUserRepo= $em->getRepository('AppBundle:CommonUser');
        $count = $routeRepo->count();
        for($i=1; $i<$count;$i++){
            $route = $routeRepo->profile($i);
            $routeId = $route->getRouteId();

            $nodes = $this->get('app.mongodb_dump_points')->getNodes($routeId);
            $angleMin = $this->get('app.mongodb_dump_points')->getAngleMin($routeId);
            $distanceMin = $this->get('app.mongodb_dump_points')->getDistanceMin($routeId);
            $hashAngleArray = $this->get('app.mongodb_dump_points')->getHashAngleArray($routeId);
            $hashDistanceArray = $this->get('app.mongodb_dump_points')->getHashDistanceArray($routeId);
            $angleDivisor = $this->get('app.mongodb_dump_points')->getAngleDivisor($routeId);
            $distanceDivisor = $this->get('app.mongodb_dump_points')->getDistanceDivisor($routeId);
            $accumulateEdge = $this->get('app.mongodb_dump_points')->getAccumulateEdge($routeId);

//            $dm = $this->get('doctrine_mongodb')->getManager();
//            $routeInfo = $dm->getRepository('AppBundle:RouteInfo')->find($routeId);
//
//            var_dump($routeInfo->getAngleDivisor());

//            var_dump($angleMin);
//            var_dump($distanceMin);
//            var_dump($hashAngleArray);
//            var_dump($hashDistanceArray);
            var_dump($angleDivisor);
            var_dump($distanceDivisor);
//            var_dump($accumulateEdge);
//            var_dump($routeId);



//            $angleDivisor =8.5615355418179E-6;
//            $distanceDivisor = 0.01820716225056;

            $angleDivisor = floatval($angleDivisor);
            $distanceDivisor=floatval($distanceDivisor);

            var_dump($angleDivisor);
            var_dump($distanceDivisor);

            //Get Truck Location - This is obtained descretly. Once necessary trucks are obtained, need to change the code.

            $trackingUnitLocateRepo = $em->getRepository('AppBundle:TrackingUnitLocate');
            $trackingUnitLocateTemp = $trackingUnitLocateRepo->profile(3);
            $lat = $trackingUnitLocateTemp->getLat();
            $lng = $trackingUnitLocateTemp->getLng();

//            $lat= 6.9273640932202;
//            $lng = 79.996716231108;
//
//            $lat= 6.8722117;
//            $lng = 79.86642;



            $routeIntId = $route->getId();
            $usersForGivenRoute = $commonUserRepo->getUsersForGivenRoute($route);
            foreach ($usersForGivenRoute as $user){
                $userId = $user->getId();
                $dumpPointId = $user->getCollectPointHashId();
                //var_dump($dumpPointId);
                $dumpPointIds = array();
                $dumpPointIds[] = $dumpPointId;
                $timeToDumpPoint  = Algorithm::getTimeToDumpPoints($routeIntId,$lat,$lng,20,$dumpPointIds,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);
                var_dump($timeToDumpPoint);

                //$userFromId = $commonUserRepo->profile($userId);
                $userFromId = $commonUserRepo->profile($userId);
                //var_dump($userFromId);

                $notificationsSent = $userFromId->getNotificationSent();
                $userPackage = $userFromId->getCommonUserPackage();

                if($userPackage!=null){
                    $userPackageId = $userPackage->getId();
                    var_dump($userPackageId);
                }else{
                    $userPackageId= 1;
                }

                 var_dump($userPackageId, $notificationsSent);
                //exit();

                if($notificationsSent==false and ($userPackageId!=1 or $userPackageId!=null) and ($timeToDumpPoint[0]<5 )){
                    var_dump('Boom');
                    $userFromId->setNotificationSent(true);
                    $userMobileNo= array();
                    $tempNo = $user->getContact();
                    var_dump($tempNo);
                    $user_name = $user->getName();
                    $userMobileNo[]=$tempNo;

                    $this->get('app.sms_messaging')->sendSMS($userMobileNo,'Hi, the Garbage truck will arive shortly. Thank you. LH');
                    var_dump('Boom2');
                }

                $userFromId->setTimeToReach($timeToDumpPoint[0]);

            }
            $em->flush();

        }
        return $this->handleResponse(0);
    }
}