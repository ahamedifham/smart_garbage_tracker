<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/11/16
 * Time: 11:06 AM
 */

namespace AppBundle\Controller\Mobile;

use AppBundle\AppBundle;
use AppBundle\Entity\CommonUser;
use AppBundle\geolocation\LatLng;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\geolocation;
use AppBundle\geolocation\SphericalGeometry;
use AppBundle\Document\Coordinates;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class APIDriverController extends APIBaseController
{
    /**
     *
     * @Route("/driver/login", name="app_api_driver_login")
     */
    public function loginAction(Request $request){
        $data=$this->handleRequest();
        $mobileNo= $data->mobileNo;
        $em = $this->getDoctrine()->getManager();
        $driverRepo = $em->getRepository('AppBundle:Driver');
        $driver = $driverRepo->getDriverFromContact($mobileNo);

        $mobileNoArray = array();

        $mobileNoArray[] = $mobileNo;



        if(sizeof($driver)!=0){
            $SMSCode = rand(1000,9999);
            $responseObj = (object)array(
                'status'=>true,
                'errors'=>null,
                'data' => (object)array(
                    'driverId'=>$driver[0]->getId(),
                    'verificationCode'=>$SMSCode
                )
                );
            //$this->get('app.sms_messaging')->sendSMS($mobileNoArray,$SMSCode);
            $smsManager = $this->get('app.mife')->getSMSManager();

            $smsManager->sendSMS($mobileNoArray,$SMSCode);
        } else{
            $responseObj = (object)array(
                'status'=>false,
                'errors'=>1,
            );
        }

        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/driver/view_driver_schedule", name="app_api_driver_view_driver_schedule")
     */
    public function viewDriverScheduleAction(Request $request){
        $data=$this->handleRequest();
        $driverId = $data->driverId;

        $todayDate = date("Y-m-d");
        $em = $this->getDoctrine()->getManager();
        $scheduleRepo = $em->getRepository('AppBundle:Schedule');
        $driverRepo = $em->getRepository('AppBundle:Driver');

        $driver = $driverRepo->profile($driverId);


        $schedulesWithDriver = $scheduleRepo->getSchedulesWithDrivers($todayDate, $driver);


        if(sizeof($schedulesWithDriver)!=0){
            $responseObj = (object)array(
                'status'=>true,
                'errors'=>null,
                'schedule' => (object)array(
                    'startTime'=>$schedulesWithDriver[0]->getStartTime(),
                    'endTime'=>$schedulesWithDriver[0]->getEndTime(),
                    'route'=>$schedulesWithDriver[0]->getRoute()->getName(),
                    'routeId' =>$schedulesWithDriver[0]->getRoute()->getRouteId(),
                    'scheduleId' =>$schedulesWithDriver[0]->getId()

                )
            );
        } else{
            $responseObj = (object)array(
                'status'=>false,
                'errors'=>1,
            );
        }


        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/driver/view_route", name="app_api_driver_view_route")
     */
    public function viewRouteAction(Request $request){
        $data=$this->handleRequest();
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
//5822fc67cbc00048445fc777
    /**
     *
     * @Route("/driver/view_start_journey_route", name="app_api_driver_view_start_journey_route")
     */
    public function viewStartJourneyRoute(Request $request){
        $data = $this->handleRequest();
        $routeId= $data->routeId;
        $dumpPoints = $this->get('app.mongodb_dump_points')->dumpPoints($routeId);
        $pathPoints = $this->get('app.mongodb_dump_points')->path($routeId);
        $recyclingPoints = $this->get('app.mongodb_dump_points')->recyclingPoints($routeId);

        $collectionPointsSent= array();
        $tempRouteDump= new \stdClass();
        //$tempRouteDump->routeId = $routeId;
        $tempDumpPoints = array();
        for($i=0; $i<sizeof($dumpPoints); $i++){
            $temp = new \stdClass();
            $temp->lat = $dumpPoints[$i][0];
            $temp->lng = $dumpPoints[$i][1];
            $tempDumpPoints[]=$temp;
        }
        $tempRouteDump->coordinates = $tempDumpPoints;
        $collectionPointsSent[]= $tempRouteDump;
       // $collectionPointsSent[]= $tempDumpPoints;

        $pinPointsSent= array();
        $tempRoutePin = new \stdClass();
        //$tempRoutePin->routeId = $routeId;
        $tempPinPoints= array();
        for($i=0; $i<sizeof($pathPoints); $i++){
            $temp =  new \stdClass();
            $temp->lat = $pathPoints[$i][0];
            $temp->lng = $pathPoints[$i][1];
            $tempPinPoints[]=$temp;
        }
        $tempRoutePin->coordinates = $tempPinPoints;
        $pinPointsSent[] = $tempRoutePin;


        $recyclingPointsSent = array();
        if($recyclingPoints!='no_recycling_points'){
            $recyclingPointsSent= null;
        } else{
            $tempRouteRecycling = new \stdClass();
            $tempRecyclingPoints = array();
            for($i=0; $i<sizeof($recyclingPoints); $i++){
                $temp = new \stdClass();
                $temp->lat = $recyclingPoints[$i][0];
                $temp->lng = $recyclingPoints[$i][1];
                $tempRecyclingPoints[]=$temp;

            }
            $tempRouteRecycling->coordinates = $tempRecyclingPoints;
            $recyclingPointsSent[] = $tempRouteRecycling;
        }

        //var_dump($collectionPointsSent);
        //exit();

        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'data'=>(object)array(
                'colPoints'=>$collectionPointsSent,
                'routes'=>$pinPointsSent,
                'recyclingPoints'=>$recyclingPointsSent
            )
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/driver/get_current_location", name="app_api_driver_get_current_location")
     */
    public function getCurrentLocation(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();
        $scheduleRepo = $em->getRepository('AppBundle:Schedule');
        $trackingUnitLocateRepo = $em->getRepository('AppBundle:TrackingUnitLocate');

        $schedule = $scheduleRepo->profile($data->scheduleId);
        $trackingUnit  = $schedule->getTruck();
        $trackingUnitLocate = $trackingUnitLocateRepo->getUnitLocate($trackingUnit);

        $lat = $trackingUnitLocate->getLat();
        $lng = $trackingUnitLocate->getLng();

        $responseObj = (object)array(
            'status'=>true,
            'errors'=>array(),
            'currentLocation'=>(object)array(
                'lat'=>$lat,
                'lng'=>$lng
            )
        );
        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/driver/send_sms", name="app_api_driver_send_sms")
     */
    public function sendSMSAction()
    {
        $response = $this->get('app.sms_messaging')->sendSMS(array('+94717664232'),'Hi Ifham');
        dump($response);
        exit;
    }

    /**
     * @Route("/driver/send_in_app_notification", name="driver_in_app_notification")
     */
    public function sendInAppNotification()
    {
        $gcm = 'csG9Yrl1xD4:APA91bFQ9q8sfPVVrpkP40G-PkM-oM-iDD28WFD61jf23zENHChx5nBw1oPh6YVNCaOL1A3ni0LJvqM1shkwnyxmISt05Kme2cMbVt6RrEONb6Xw68gwKdYSr2G_kJr-qXbL76F-cDzj';
        $response = $this->get('app.in_app_notification')->sendInAppNotification($gcm,'Title Check', 'Message');
        var_dump($response);
    }

    /**
     *
     * @Route("/driver/set_start_time", name="app_api_driver_set_start_time")
     */

    public function setStartTimeAction(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();

        $driverRepo = $em->getRepository('AppBundle:Driver');
        $driverHistoryRepo = $em->getRepository('AppBundle:DriverHistory');
        $scheduleRepo = $em->getRepository('AppBundle:Schedule');


        $driverId = $data->driverId;
        $scheduleId = $data->scheduleId;


        $driver = $driverRepo->profile($driverId);
        $schedule = $scheduleRepo->profile($scheduleId);
        $trackingUnit = $schedule->getTruck();
        $route = $schedule->getRoute();

        $errors = $driverHistoryRepo->register($data,$driver,$schedule,$trackingUnit,$route);

        $responseObj = (object)array(
            'status'=>!count($errors) ? true:false,
            'errors'=>$errors
        );

        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/driver/set_end_time", name="app_api_driver_set_end_time")
     */
    public function setEndTimeAction(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();

        $driverRepo = $em->getRepository('AppBundle:Driver');
        $driverHistoryRepo = $em->getRepository('AppBundle:DriverHistory');
        $scheduleRepo = $em->getRepository('AppBundle:Schedule');
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');
        $commonUserHistoryRepo = $em->getRepository('AppBundle:CommonUserHistory');

        $driverId = $data->driverId;
        $scheduleId = $data->scheduleId;

        $driver = $driverRepo->profile($driverId);
        $schedule = $scheduleRepo->profile($scheduleId);

        $driverHistory = $driverHistoryRepo->getHistoryFromDriverScheduleDate($data,$driver,$schedule);
        $endTime = new \DateTime($data->endTime);

        $driverHistory->setEndTime($endTime);

        $route = $schedule->getRoute();
        $usersInRoute = $commonUserRepo->getUsersForGivenRoute($route);
        $todayDate = date("Y-m-d");



        foreach ($usersInRoute as $user){
            $commonUserHistoryRepo->addNewRecord($todayDate,$user, $driver, $schedule);
        }
        
        $errors = $em->flush();

        $responseObj = (object)array(
            'status'=>!count($errors) ? true:false,
            'errors'=>$errors
        );

        return $this->handleResponse($responseObj);
    }
}