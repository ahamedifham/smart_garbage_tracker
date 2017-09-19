<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/8/16
 * Time: 11:03 AM
 */

namespace AppBundle\Controller\Mobile;


use AppBundle\AlertAlgorithm\Algorithm;
use AppBundle\AppBundle;
use AppBundle\Entity\CommonUser;
use AppBundle\geolocation\LatLng;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\geolocation;
use AppBundle\geolocation\SphericalGeometry;
use AppBundle\Document\Coordinates;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


//include 'spherical-geometry.class.php';
/**
 * @Route("/api")
 */
class APICommonUserController extends APIBaseController
{

    private $doctrineORM;
    private $doctrineODM;
    private $redis;

//    public function __construct($doctrineORM,$doctrineODM,$redis)
//    {
//        $this->doctrineORM  = $doctrineORM;
//        $this->doctrineODM  = $doctrineODM;
//        $this->redis        = $redis;
//    }

    /**
     *
     * @Route("/user/login", name="app_api_user_login")
     */
    public function loginAction(Request $request){
        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'data'=>(object)array(
                'auth'=>(object)array(
                    'token'=>'1234',
                    'id' =>'1234'
                ),
                'config'=>(object)array(
                    'select' => (object)array(
                        'fieldName1'=>array(),
                        'fieldName2'=>array(),
                    ),
                    'route' => (object)array(
                        'routeAliase1'=>  'route url 1',
                        'routeAliase2'=> ' route url 2',
                    )
                )

            )
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/get_routes", name="app_api_user_get_routes")
     */
    public function getRoutesAction(Request $request)
    {
        $data = $this->handleRequest();
        $coordinates = $data->coordinates;
        $lat = $coordinates->lat;
        $lng = $coordinates->lng;

        $baseLatLng = new LatLng(floatval($lat), floatval($lng));
        $rectangle = SphericalGeometry::computeBounds($baseLatLng, 10000);


        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Route');
        $count = $repo->count();


        $mongodbRouteIds = array();
        $eligibleIds = array();
        $notEligibleIds = array();
        $finalDumpPoints= array();
        $finalPinPoints= array();
        for ($i = 1; $i <= $count; $i++) {
            $route = $repo->find($i);
            array_push($mongodbRouteIds, $route->getRouteId());
        }

        for ($i = 0; $i < sizeof($mongodbRouteIds); $i++) {
            $dumpPoints = $this->get('app.mongodb_dump_points')->dumpPoints($mongodbRouteIds[$i]);
            //var_dump(sizeof($dumpPoints));
            for ($k = 0; $k < sizeof($dumpPoints); $k++) {
                $tempLocation = new LatLng($dumpPoints[$k][0], $dumpPoints[$k][1]);
                $contains = $rectangle->contains($tempLocation);
                if ($contains == true) {
                    array_push($eligibleIds, $mongodbRouteIds[$i]);
                    break;
                } else {
                    array_push($notEligibleIds, $mongodbRouteIds[$i]);
                }
            }
        }

      //  var_dump($eligibleIds);
        if (sizeof($eligibleIds) == 0) {
            $responseObj = (object)array(
                'status' => true,
                'errors' => array(),
            );
            return $this->handleResponse($responseObj);
        } else {

            //Get the User Location
                $userLocationLat = floatval($lat);
                $userLocationLng = floatval($lng);
            //Get the dump points
                for ($i=0; $i<sizeof($eligibleIds);$i++){
                    $tempDumpPoints = $this->get('app.mongodb_dump_points')->dumpPoints($eligibleIds[$i]);
                    array_push($finalDumpPoints, $tempDumpPoints);
                }
                //var_dump($finalDumpPoints);


//            $colPoints= array();
//            foreach ($finalDumpPoints as $point){
//                foreach ($point as $finalPoint){
//                    $temp = new \stdClass();
//                    $temp->lat = $finalPoint[0];
//                    $temp->lng = $finalPoint[1];
//
//                    $colPoints[]=$temp;
//                }
//            }

            //Get the pin points

                for ($i=0; $i<sizeof($eligibleIds); $i++){
                    $tempPinPoints = $this->get('app.mongodb_dump_points')->pinPoints($eligibleIds[$i]);
                    array_push($finalPinPoints, $tempPinPoints);
                }
//            $tempCount=0;
//            //$pinPoints= array();
//            foreach ($finalPinPoints as $points){
//                $temp = new \stdClass();
//                $temp->routeId = $eligibleIds[$tempCount];
//                foreach ($points as $finalPoint){
//
//                    $temp->lat = $finalPoint[0];
//                    $temp->lng = $finalPoint[1];
//                    $pinPoints[]=$temp;
//                }
//                $tempCount++;
//            }

//            $routesSent= array();
//            for ($i=0; $i<sizeof($eligibleIds); $i++){
//                $tempRoute= new \stdClass();
//                $tempRoute->routeId = $eligibleIds[$i];
//                for($j=0; $j<sizeof($finalPinPoints[$i]); $j++){
//                    $temp = new \stdClass();
//                    $temp->lat = $finalPinPoints[$i][$j][0];
//                    $temp->lng = $finalPinPoints[$i][$j][1];
//                    $pinPoints[]=$temp;
//                }
//                $tempRoute->coordinates = $pinPoints;
//                $routesSent[]= $tempRoute;
//            }

            $collectionPointsSent= array();
            for ($i=0; $i<sizeof($eligibleIds); $i++){
                $dumpPoints= array();
                $tempRoute= new \stdClass();
                $tempRoute->routeId = $eligibleIds[$i];
                for($j=0; $j<sizeof($finalDumpPoints[$i]); $j++){

                    $temp = new \stdClass();

                    $temp->lat = $finalDumpPoints[$i][$j][0];
                    $temp->lng = $finalDumpPoints[$i][$j][1];
                    $dumpPoints[]=$temp;
                }
                $tempRoute->coordinates = $dumpPoints;
                $collectionPointsSent[]= $tempRoute;
            }

            $routePointsSent= array();
            for ($i=0; $i<sizeof($eligibleIds); $i++){
                $pinPoints= array();
                $tempRoute= new \stdClass();
                $tempRoute->routeId = $eligibleIds[$i];
                for($j=0; $j<sizeof($finalPinPoints[$i]); $j++){
                    $temp = new \stdClass();
                    $temp->lat = $finalPinPoints[$i][$j][0];
                    $temp->lng = $finalPinPoints[$i][$j][1];
                    $pinPoints[]=$temp;
                }
                $tempRoute->coordinates = $pinPoints;
                $routePointsSent[]= $tempRoute;
            }



            $responseObj = (object)array(
                'status' => sizeof($eligibleIds)? true:false,
                'errors' => array(),
                'data' => (object)array(
                    'userLocation' => (object)array(
                        'coordinates' => (object)array(
                            'lat' => $userLocationLat,
                            'lng' => $userLocationLng
                        )
                    ),
                    'colPoints' => $collectionPointsSent,
                    'routes' => $routePointsSent,
                )
            );
           var_dump($responseObj);
            return $this->handleResponse($responseObj);
        }
    }

    /**
     *
     * @Route("/user/set_route", name="app_api_user_set_route")
     */
    public function setRouteAction(Request $request){

        $data = $this->handleRequest();
        $coordinates = $data->coordinates;
        $lat = floatval($coordinates->lat);
        $lng = floatval($coordinates->lng);
        $routeId= $data->routeId;


        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $routeRepo= $em->getRepository('AppBundle:Route');

        $user= $commonUserRepo->profile($data->userId);
        $route = $routeRepo->returnRoute($routeId);

        $user->setCollectPointLat($lat);
        $user->setCollectPointLng($lng);
        $user->setRoute($route);


        $errors= $em->flush();

        $responseObj = (object)array(
            'status'=>count($errors)? false:true,
            'errors'=>$errors,
        );

//        var_dump($user);
//        exit();

        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/vehicle_tracking_initialize", name="app_api_user_vehicle_tracking_initialize")
     */
    public function vehicleTrackingInitializeAction(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $scheduleRepo = $em->getRepository('AppBundle:Schedule');
        $user= $commonUserRepo->profile($data->userId);

        $userLocationLat= $user->getCollectPointLat();
        $userLocationLng = $user->getCollectPointLng();

        $route= $user->getRoute();
        $routeId = $route->getRouteId();

        $todayDate = date("Y-m-d");
        $schedules = $scheduleRepo->getSchedulesWithRoute($todayDate, $route);
//        var_dump(sizeof($schedules));
//        var_dump(($schedules));

        if(sizeof($schedules)==0){
            $scheduleAvailable = false;
        }else{
            $scheduleAvailable = true;
        }

        $dumpPoints = $this->get('app.mongodb_dump_points')->dumpPoints($routeId);
        //$pinPoints = $this->get('app.mongodb_dump_points')->pinPoints($routeId);
        $pinPoints = $this->get('app.mongodb_dump_points')->path($routeId);

        $collectionPointsSent= array();
        $tempRouteDump= new \stdClass();
        $tempRouteDump->routeId = $routeId;
        $tempDumpPoints = array();
        for($i=0; $i<sizeof($dumpPoints); $i++){
            $temp = new \stdClass();
            $temp->lat = $dumpPoints[$i][0];
            $temp->lng = $dumpPoints[$i][1];
            $tempDumpPoints[]=$temp;
        }
        $tempRouteDump->coordinates = $tempDumpPoints;
        $collectionPointsSent[]= $tempRouteDump;
        $collectionPointsSent[]= $tempDumpPoints;

        $pinPointsSent= array();
        $tempRoutePin = new \stdClass();
        $tempRoutePin->routeId = $routeId;
        $tempPinPoints= array();
        for($i=0; $i<sizeof($pinPoints); $i++){
            $temp =  new \stdClass();
            $temp->lat = $pinPoints[$i][0];
            $temp->lng = $pinPoints[$i][1];
            $tempPinPoints[]=$temp;
        }
        $tempRoutePin->coordinates = $tempPinPoints;
        $pinPointsSent[] = $tempRoutePin;

        $todayDate = date("Y-m-d");
        $schedules = $scheduleRepo->getSchedules($todayDate);
//        var_dump($schedules);


        //var_dump($collectionPointsSent);
        //exit();

        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'data'=>(object)array(
                'userLocation'=>(object)array(
                    'coordinates'=>(object)array(
                        'lat'=>$userLocationLat,
                        'lng'=>$userLocationLng,
                    )
                ),
                'colPoints'=>$collectionPointsSent,
                'routes'=>$pinPointsSent,
                'scheduleAvailable' => $scheduleAvailable
            )
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/vehicle_location", name="app_api_user_vehicle_location")
     */
    public function vehicleLocationAction(Request $request){
        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'data'=>(object)array(
                'vehicleInfo'=>(object)array(
                    'approTime'=>'24',
                    'distance'=>'1',
                    'coordinates'=>(object)array(
                        'lat'=>'79.68',
                        'lng'=>'6.08'
                    )
                )
            )
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/profile", name="app_api_user_profile")
     */
    public function profileAction(Request $request){
        $data = $this->handleRequest();

        $temp=$this->getDoctrine()->getRepository('AppBundle:CommonUser')->profile($data->userId);

        $responseObj = (object)array(
            'status'=>true,
            'errors'=>$temp,
            'data'=>(object)array(
                'name'=> $temp->getName(),
                'username'=> $temp->getUsername(),
                'phone' => $temp->getContact(),
                'email' => $temp->getEmail(),
                'coordinates' => (object)array(
                    'lat'=>$temp->getCollectPointLat(),
                    'lng'=>$temp->getCollectPointLng(),
                )
            )
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/profile_update", name="app_api_user_profile_update")
     */
    public function profileUpdateAction(Request $request){
        $data = $this->handleRequest();

        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $product= $commonUserRepo->profile($data->userId);
        if($data->phone){
            $product->setContact($data->phone);
        }
        if($data->email){
            $product->setEmail($data->email);
        }
        if($data->newPassword){
            $password = $this->get('security.password_encoder')
                ->encodePassword(new CommonUser(), $data->newPassword );
            $data->newPassword = $password;
            $product->setPassword($data->newPassword);
        }
        $errors= $em->flush();

        $responseObj = (object)array(
            'status'=>count($errors)? false:true,
            'errors'=>$errors,
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/raise_complaint", name="app_api_user_raise_complaint")
     */
    public function raiseComplaintAction(Request $request){
        $data = $this->handleRequest();

        $em = $this->getDoctrine()->getManager();
        $complaintRepo = $em->getRepository('AppBundle:Complaints');

        $errors = $complaintRepo->register($data);


        $responseObj = (object)array(
            'status'=>count($errors)? false:true,
            'errors'=>array(),
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/collection_history", name="app_api_user_collection_history")
     */
    public function collectionHistoryAction(Request $request){
        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'data'=>(object)array(
                'des'=>(object)array(
                    'record1'=>(object)array(
                        'time'=>'24',
                        'situation'=>'OK'
                    ),
                    'record2'=>(object)array(
                        'time'=>'23',
                        'situation'=>'OK'
                    )
                )

            )
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/set_dump_point", name="app_api_user_set_dump_point")
     */
    public function setDumpPoint(Request $request){
        $data = $this->handleRequest();
        $coordinates = $data->coordinates;
        $lat = floatval($coordinates->lat);
        $lng = floatval($coordinates->lng);

        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $user= $commonUserRepo->profile($data->userId);
        $user->setCollectPointLat($lat);
        $user->setCollectPointLng($lng);

//        $token = $this->get('security.context')->getToken()->getToken();
//        $accessTokenManager = $this->get('fos_oauth_server.access_token_manager.default');
//        $accessToken = $accessTokenManager->findTokenBy(array('token' => $token));
//        $oauthUser=$accessToken->getClient()->getUser();
//
//        var_dump($oauthUser);

        $repo = $em->getRepository('AppBundle:Route');
        $count = $repo->count();

        $routeName = array();
        $routeCode = array();
        $routeId = array();
        for ($i = 1; $i <= $count; $i++) {
            $route = $repo->find($i);
            array_push($routeName, $route->getName());
            array_push($routeCode, $route->getCode());
            array_push($routeId, $route->getRouteId());
        }
        $routes= array();
        for ($i=0; $i<sizeof($routeName); $i++){
            $temp = new \stdClass();
            $temp->routeName = $routeName[$i];
            $temp->routeCode = $routeCode[$i];
            $temp->routeId = $routeId[$i];
            $routes[]=$temp;
        }

        $errors= $em->flush();

//        $responseObj = (object)array(
//            'status'=>count($errors)? false:true,
//            'errors'=>$errors,
//        );
//        return $this->handleResponse($responseObj);
        $responseObj = (object)array(
            'status' => count($errors)? false:true,
            'errors' => array(),
            'routes' => $routes,
        );

        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/get_all_routes", name="app_api_user_get_all_routes")
     */
    public function getAllRoutes(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Route');
        $count = $repo->count();

        $routeName = array();
        $routeCode = array();
        $routeId = array();
        for ($i = 1; $i <= $count; $i++) {
            $route = $repo->find($i);
            array_push($routeName, $route->getName());
            array_push($routeCode, $route->getCode());
            array_push($routeId, $route->getRouteId());
        }
        $routes= array();
        for ($i=0; $i<sizeof($routeName); $i++){
            $temp = new \stdClass();
            $temp->routeName = $routeName[$i];
            $temp->routeCode = $routeCode[$i];
            $temp->routeId = $routeId[$i];
            $routes[]=$temp;
        }

        $responseObj = (object)array(
            'status' => sizeof($routes)? true:false,
            'errors' => array(),
            'routes' => $routes,
        );


        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/view_route", name="app_api_user_view_route")
     */
    public function viewRoute(Request $request){
        $data = $this->handleRequest();
        $routeId= $data->routeId;
        $pinPoints = $this->get('app.mongodb_dump_points')->path($routeId);

        $pinPointsSent= array();
        $tempRoutePin = new \stdClass();
        $tempRoutePin->routeId = $routeId;
        $tempPinPoints= array();
        for($i=0; $i<sizeof($pinPoints); $i++){
            $temp =  new \stdClass();
            $temp->lat = $pinPoints[$i][0];
            $temp->lng = $pinPoints[$i][1];
            $tempPinPoints[]=$temp;
        }
        $tempRoutePin->coordinates = $tempPinPoints;
        $pinPointsSent[] = $tempRoutePin;

        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'routes'=>$pinPointsSent,
        );
        return $this->handleResponse($responseObj);

    }

    /**
     * When a user is assigned into a route
     * this function is called to update redis user structure
     * @param CommonUser $user
     */
    private function LoadUsertoRedis(\AppBundle\Entity\CommonUser $user){
        $serializer = new Serializer(
            array(new GetSetMethodNormalizer(), new ArrayDenormalizer()),
            array(new JsonEncoder())
        );
        $alertAlgo = $this->get('app.alert_algo');

        $result= $alertAlgo->UpdateNodeStruct($user,$serializer);

       // return $result;
    }

    /**
     *
     * @Route("/user/set_user_route", name="app_api_user_set_user_route")
     */
    public function setUserRoute(Request $request){
        $data = $this->handleRequest();

        $routeId= $data->routeId;
        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $routeRepo= $em->getRepository('AppBundle:Route');

        $user= $commonUserRepo->profile($data->userId);
        $route = $routeRepo->returnRoute($routeId);

        $user->setRoute($route);

        //call above method to update user struct
        //$this->LoadUsertoRedis($user);

        shell_exec('php app/console app:generate-struct');


        $commonUserPackageRepo = $em->getRepository('AppBundle:CommonUserPackage');
        $packageCount = $commonUserPackageRepo->count();
       // $roleCount = 2;
//        var_dump($roleCount);
//        exit();

//        $pinPointsSent= array();
//        $tempRoutePin = new \stdClass();
//        $tempRoutePin->routeId = $routeId;
//        $tempPinPoints= array();
//        for($i=0; $i<sizeof($pinPoints); $i++){
//            $temp =  new \stdClass();
//            $temp->lat = $pinPoints[$i][0];
//            $temp->lng = $pinPoints[$i][1];
//            $tempPinPoints[]=$temp;
//        }
//        $tempRoutePin->coordinates = $tempPinPoints;
//        $pinPointsSent[] = $tempRoutePin;

        $plans = array();
        $tempPlans = new \stdClass();
        $planDescription = array();

        for($i=1 ; $i<$packageCount+1 ; $i++){
            $commonUserPackage = $commonUserPackageRepo->profile($i);
            $temp =  new \stdClass();
            $temp->name = $commonUserPackage->getName();
            $temp->description = $commonUserPackage->getDescription();
            $temp->fee = $commonUserPackage->getFee();
            $temp->frequency = $commonUserPackage->getFrequency()->getFrequency();
            $temp->packageId = $i;
            $plans[] = $temp;
        }
        //$plans[] = $tempPlans;

        $errors= $em->flush();

        //The following is to add the new dump point to the route we selected

//        $dumpPointLat = $user->getCollectPointLat();
//        $dumpPointLng = $user->getCollectPointLng();
//

        //The following is used to automatically assign for a point in route
        $dumpPointLat = $user->getAssignedPointLat();
        $dumpPointLng = $user->getAssignedPointLng();

        $dumpPointLatLng = array();
        $dumpPointLatLng[]=$dumpPointLat;
        $dumpPointLatLng[]=$dumpPointLng;

        $dm = $this->get('doctrine_mongodb')->getManager();
        $routeInfo = $dm->getRepository('AppBundle:RouteInfo')->find($routeId);



        $existingDumpPoints = $this->get('app.mongodb_dump_points')->dumpPoints($routeId);
        if($existingDumpPoints!='no_dump_points'){
            $dumpPoints=array();
        }else{
            $dumpPoints=$existingDumpPoints;
        }

        $dumpPoints[] = $dumpPointLatLng;
        
        $dumpPointLats =array();
        $dumpPointLngs = array();
        for ($i=0 ; $i<sizeof($dumpPoints); $i++){
            $dumpPointLats[] = $dumpPoints[$i][0];
            $dumpPointLngs[] = $dumpPoints[$i][1];
        }


        $routeInfo->removeAllDumpPoints();

        for ($i=0; $i<sizeof($dumpPointLats); $i++){
            $coordinatesDump= new Coordinates();
            $coordinatesDump->setLat($dumpPointLats[$i]);
            $coordinatesDump->setLng($dumpPointLngs[$i]);
            $routeInfo->addDumpPoint($coordinatesDump);
        }

        $dm->flush();

        //Adding part is over

        //Selecting Plans: Still Hardcoded


        $responseObj = (object)array(
            'status'=>count($errors)? false:true,
            'errors'=>$errors,
            'plans'=>$plans,
        );

        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/set_user_feedback", name="app_api_user_set_user_feedback")
     */
    public function setUserFeedback(Request $request){
        $data = $this->handleRequest();

        $em = $this->getDoctrine()->getManager();
        $commonUserFeedbackRepo = $em->getRepository('AppBundle:CommonUserFeedback');

        $errors = $commonUserFeedbackRepo->register($data);

        $responseObj = (object)array(
            'status'=>!count($errors) ? true:false,
            'errors'=>$errors,
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/set_driver_feedback", name="app_api_user_set_driver_feedback")
     */
    public function setDriverFeedback(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();
        $driverFeedbackRepo = $em->getRepository('AppBundle:DriverFeedback');

        $errors = $driverFeedbackRepo->register($data);

        $responseObj = (object)array(
            'status'=>!count($errors) ? true:false,
            'errors'=>$errors,
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/set_user_package", name="app_api_user_set_user_package")
     */
    public function setUserPackage(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();
        $customerRoleRepo = $em->getRepository('AppBundle:CommonUserPackage');
        $customerRepo = $em->getRepository('AppBundle:CommonUser');

        $userPackage = $customerRoleRepo->profile($data->packageId);
        $user = $customerRepo->profile($data->userId);
        
        $user->setCommonUserPackage($userPackage);
        $user->setRegistrationStatus(1);

        $errors= $em->flush();
        $responseObj = (object)array(
            'status'=>count($errors)? false:true,
            'errors'=>$errors,
        );

        shell_exec('php app/console app:generate-struct');

        return $this->handleResponse($responseObj);
    }

    /**
     * This private function is used to get the time from Redis table
     * this function is called to update redis user structure
     *
     */
    private function getTimeFromRedisForAUser($userId){
        $serializer = new Serializer(
            array(new GetSetMethodNormalizer(), new ArrayDenormalizer()),
            array(new JsonEncoder())
        );
        $alertAlgo = $this->get('app.alert_algo');

        $result= $alertAlgo->getTimeFromRedisTable($userId);

         return $result;
    }

    /**
     *
     * @Route("/user/get_current_location", name="app_api_user_get_current_location")
     */
    public function getCurrentLocation(Request $request){ // TODO get\info from redis
        $redis  = $this     ->redis;

        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();

        $customerRepo = $em->getRepository('AppBundle:CommonUser');
        $trackingUnitLocateRepo = $em->getRepository('AppBundle:TrackingUnitLocate');
        $trackingUnitRepo = $em->getRepository('AppBundle:TrackingUnit');
        $scheduleRepo = $em->getRepository('AppBundle:Schedule');



        $user = $customerRepo->profile($data->userId);
        $route = $user->getRoute();
//        $trackingUnit = $trackingUnitRepo->getTrackingUnitFromRoute($route);
//        $trackingUnitLocate = $trackingUnitLocateRepo->getUnitLocate($trackingUnit);


        //Get the Locations Directly : Will be comented for emergency use
        $trackingUnitLocateTemp = $trackingUnitLocateRepo->profile(3);
        $lat = $trackingUnitLocateTemp->getLat();
        $lng = $trackingUnitLocateTemp->getLng();

        $todayDate = date("Y-m-d");
        $schedules = $scheduleRepo->getSchedulesWithRoute($todayDate, $route);


//        var_dump(sizeof($schedules));
//        var_dump(($schedules));

        if(sizeof($schedules)==0){
            $scheduleAvailable = false;
        }else{
            $scheduleAvailable = true;
            $tempSchedule = $schedules[0];

            $scheduledTruckMsisdn = $tempSchedule->getTruck()->getMsisdn();
            $result = $this->get('app.mife')->getILocatorManager()->getCurrentLocation($scheduledTruckMsisdn);

            $lat        =$result[0] ->lat;
            $lng        =$result[0] ->lon;

        }

//        $lat = $trackingUnitLocate->getLat();
//        $lng = $trackingUnitLocate->getLng();

        //Calculate Approximate TIme
        $timeFromTable = $user->getTimeToReach();
        if($timeFromTable==null){
            $timeSent = 'Calculation Not Available';
        }elseif ($timeFromTable<0){
            $timeSent = '0';
        }elseif ($timeFromTable==0){
            $timeSent= '0';

        }elseif ($timeFromTable>0){
            $timeSent = strval($timeFromTable);
        }
        
        $timeFromRedisTable = $this->getTimeFromRedisForAUser($data->userId);
        if($timeFromRedisTable != null){
            $timeSent = $timeFromRedisTable;
        }
        

        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'data'=>(object)array(
                'vehicleInfo'=>(object)array(
                    'approTime'=>$timeSent,
                    'distance'=>'1',
                    'coordinates'=>(object)array(
                        'lat'=>$lat,
                        'lng'=>$lng
                    )
                ),
                'scheduleAvailable' => $scheduleAvailable
            )
        );
        return $this->handleResponse($responseObj);
    }


    /**
     *
     * @Route("/user/set_gcm", name="app_api_user_set_gcm")
     */
    public function setGcm(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();

        $customerRepo = $em->getRepository('AppBundle:CommonUser');
        $user = $customerRepo->profile($data->userId);
        $user->setGcmCode($data->gcmCode);

        $errors= $em->flush();
        $responseObj = (object)array(
            'status'=>count($errors)? false:true,
            'errors'=>$errors,
        );

        return $this->handleResponse($responseObj);

    }

    /**
     *
     * @Route("/user/auto_pick_route", name="app_api_user_set_dump_point_and_auto_pick_route")
     */
    public function autoPickRouteAction(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();
        $customerRepo = $em->getRepository('AppBundle:CommonUser');
        $routeRepo= $em->getRepository('AppBundle:Route');
        $count = $routeRepo->count();

        $user = $customerRepo->profile($data->userId);

        $coordinates = $data->coordinates;
        $lat = floatval($coordinates->lat);
        $lng = floatval($coordinates->lng);

        $user->setCollectPointLat($lat);
        $user->setCollectPointLng($lng);
        
        $routeIds = array();
        
        for ($i=1; $i<$count+1; $i++){
            $route = $routeRepo->profile($i);

            $routeId= $route->getRouteId();
            $routeIds[] = $routeId;
        }

        $pathPointsOfTheFirstRoute = $this->get('app.mongodb_dump_points')->path($routeIds[0]);
        $tempLat = $pathPointsOfTheFirstRoute[0][0];
        $tempLng = $pathPointsOfTheFirstRoute[0][1];

        $customerLocation =  new LatLng(floatval($lat), floatval($lng));
        $initialLocation = new LatLng(floatval($tempLat), floatval($tempLng));

        $initialDistance = SphericalGeometry::computeDistanceBetween($customerLocation,$initialLocation);

        $minimumDistanceRouteId =$routeIds[0];
        $minimumDistanceRouteIdsForFiveRoutes = array();
        $minimumDistanceArray = array();

        $closerPointLat=$lat;
        $closerPointLng=$lng;

        for($k=0 ; $k<sizeof($routeIds); $k++){
            $tempRouteId = $routeIds[$k];
            $tempPathPoints = $this->get('app.mongodb_dump_points')->path($tempRouteId);


//            for($j=0; $j<sizeof($tempPathPoints); $j++){
//                $tempPointLat = $tempPathPoints[$j][0];
//                $tempPointLng = $tempPathPoints[$j][1];
//                $tempLocationLatLng = new LatLng(floatval($tempPointLat), floatval($tempPointLng));
//                $tempDistance = SphericalGeometry::computeDistanceBetween($customerLocation, $tempLocationLatLng);
//                if($tempDistance<$initialDistance){
//                    $initialDistance=$tempDistance;
//                    $minimumDistanceRouteId= $tempRouteId;
//                    $closerPointLat = $tempPointLat;
//                    $closerPointLng = $tempPointLng;
//                }
//            }

            //Calculate the Initial Distance between first point of each route and save it in a varible
            $tempInitialLocationLatLng = new LatLng(floatval($tempPathPoints[0][0]),floatval($tempPathPoints[0][1]));

            $tempInitialDistance = SphericalGeometry::computeDistanceBetween($customerLocation, $tempInitialLocationLatLng);

            for($h=0; $h<sizeof($tempPathPoints);$h++){
                $tempPointLat = $tempPathPoints[$h][0];
                $tempPointLng = $tempPathPoints[$h][1];
                $tempLocationLatLng = new LatLng(floatval($tempPointLat), floatval($tempPointLng));
                $tempDistance = SphericalGeometry::computeDistanceBetween($customerLocation, $tempLocationLatLng);

                if($tempInitialDistance>$tempDistance){

                    $tempInitialDistance = $tempDistance;
                    $minimumDistanceArray[$tempRouteId] = $tempInitialDistance;

                }
            }

        }

        asort($minimumDistanceArray);


        $routes= array();

        if(sizeof($minimumDistanceArray)>5){
            $bestFiveRoutes = array_keys(array_slice($minimumDistanceArray,0,5));
        }else{
            $bestFiveRoutes=array_keys($minimumDistanceArray);
        }


        foreach ($bestFiveRoutes as $routeIdSelected){
            $routeFromRepo = $routeRepo->returnRoute($routeIdSelected);
            $temp = new \stdClass();
            $temp->routeName = $routeFromRepo->getName();
            $temp->routeCode = $routeFromRepo->getCode();
            $temp->routeId = $routeIdSelected;
            $routes[]=$temp;
        }


        $user->setAssignedPointLat($closerPointLat);
        $user->setAssignedPointLng($closerPointLng);

        $finalRoute = $routeRepo->returnRoute($minimumDistanceRouteId);

        $routeToSend = array();

        $temp = new \stdClass();
        $temp->routeName = $finalRoute->getName();
        $temp->routeCode = $finalRoute->getCode();
        $temp->routeId = $minimumDistanceRouteId;

//        if($initialDistance<1000){
//            $routeToSend[] = $temp;
//        }else{
//            $routeToSend = array();
//        }

        $routeToSend[] = $temp;
        //var_dump($closerPointLat,$closerPointLng);

//5827f59e8589a7910e258c19

        $errors= $em->flush();

        $responseObj = (object)array(
            'status' => count($errors)? false:true,
            'errors' => array(),
            'routes' => $routes,
        );

        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/set_user_history", name="app_api_user_set_user_history")
     */
    public function setUserHistoryAction(Request $request){
        $data = $this->handleRequest();

        $em = $this->getDoctrine()->getManager();
        $historyRepo = $em->getRepository('AppBundle:CommonUserHistory');
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $driverRepo = $em->getRepository('AppBundle:Driver');
        $scheduleRepo = $em->getRepository('AppBundle:Schedule');

        $user = $commonUserRepo->profile($data->userId);
        $route = $user->getRoute();

        $todayDate = date("Y-m-d");
        $schedules = $scheduleRepo->getSchedulesWithRoute($todayDate, $route);

        if (sizeof($schedules)>0){
            $driver = $schedules[0]->getDriver();
            $schedule = $schedules[0];
        }else{
            $driver = null;
            $schedule = null;
        }

        $errors = $historyRepo->register($data, $user, $driver, $schedule, $todayDate);

        $responseObj = (object)array(
            'status'=>count($errors)? false:true,
            'errors'=>array(),
        );
        return $this->handleResponse($responseObj);
    }


    /**
     *
     * @Route("/user/get_user_history", name="app_api_user_get_user_history")
     */
    public function getUserHistoryAction(Request $request){
        $data = $this->handleRequest();

        $em = $this->getDoctrine()->getManager();
        $historyRepo = $em->getRepository('AppBundle:CommonUserHistory');
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $user = $commonUserRepo->profile($data->userId);

        $history = $historyRepo->getHistoryFromUser($user);

        $historySent = array();

        for($i=0; $i<sizeof($history); $i++){
            $tempHistory = $history[$i];
            $temp = new \stdClass();
            $temp->date = $tempHistory->getDate();
            $temp->isCollected = $tempHistory->getIsCollected();
            $historySent[] = $temp;
        }

        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'data'=>$historySent
        );
        return $this->handleResponse($responseObj);

    }

    /**
     *
     * @Route("/user/edit_user_history", name="app_api_user_edit_user_history")
     */
    public function editUserHistory(Request $request){
        $data = $this->handleRequest();

        $em = $this->getDoctrine()->getManager();
        $historyRepo = $em->getRepository('AppBundle:CommonUserHistory');
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $user = $commonUserRepo->profile($data->userId);

        $historyArray = $historyRepo->getHistoryFromDateAndUser($data,$user);

        if(sizeof($historyArray !=0)){
            $history = $historyArray[0];

            if($data->isCollected=="True"){
                $isCollected = True;
            }elseif ($data->isCollected=="False"){
                $isCollected = False;
            }
            $history->setIsCollected($isCollected);
        }

        $errors = $em->flush();

        $responseObj = (object)array(
            'status'=>count($errors)? false:true,
            'errors'=>array(),
        );
        return $this->handleResponse($responseObj);

    }

    /**
     *
     * @Route("/user/get_user_location_center", name="app_api_user_get_user_location_center")
     */
    public function getUserLocationCenterAction(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $user = $commonUserRepo->profile($data->userId);

        $lat = $user->getCollectPointLat();
        $lng = $user->getCollectPointLng();


        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'coordinates'=>(object)array(
                'lat'=>$lat,
                'lng'=>$lng
            )
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/get_user_complaints", name="app_api_user_get_user_complaints")
     */
    public function getUserComplaints(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $complaintsRepo = $em->getRepository('AppBundle:Complaints');
        $user = $commonUserRepo->profile($data->userId);

        $complaints = $complaintsRepo->getComplaintsFromUser($user);

        $complaintsSent = array();

        for($i=0; $i<sizeof($complaints); $i++){
            $tempComplaint = $complaints[$i];
            $temp = new \stdClass();
            $temp->id = $tempComplaint->getId();
            $temp->date = $tempComplaint->getDate();
            $temp->time = $tempComplaint->getTime();
            $temp->description = $tempComplaint->getDescription();
            $complaintsSent[] = $temp;
        }

        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'data'=>$complaintsSent
        );
        return $this->handleResponse($responseObj);

    }
}