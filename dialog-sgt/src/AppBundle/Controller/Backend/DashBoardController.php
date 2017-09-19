<?php

namespace AppBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class DashBoardController extends Controller
{
    /**
     *
     * @Route("/home", name="app_dashboard")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need

        $todayDate = date("Y-m-d");
        $em = $this->getDoctrine()->getManager();
        $scheduleRepo = $em->getRepository('AppBundle:Schedule');
        $complaintsRepo = $em->getRepository('AppBundle:Complaints');
        $schedules = $scheduleRepo->getSchedules($todayDate);
        $todayComplaintList = $complaintsRepo->getTodayComplaints($todayDate);
        // var_dump(sizeof($schedules));
        $routeIds= array();
        foreach ($schedules as $schedule){
            $tempId = $schedule->getId();
            $tempSchedule = $scheduleRepo->profile($tempId);
            $route= $tempSchedule->getRoute();
            $routeIds[] = $route->getRouteId();
        }

        return $this->render('backend/dashboard/dashboard.html.twig',array('page_title'=>'DashBoard', 'schedule_no'=>sizeof($routeIds), 'table_title'=>'Today Complaints',
            'list'=>$todayComplaintList));
    }

    /**
     *
     * @Route("/home/get-truck-location", name="app_dashboard_get_truck_location")
     */
    public function truckLocateAction(Request $request)
    {
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
       // $trucksLocations=$em->getRepository('AppBundle:TrackingUnitLocate')->findBy(array('status'=>1));
        $trucksLocations=$em->getRepository('AppBundle:TrackingUnit')->findBy(array('status'=>1));

        $locations = array();
        foreach($trucksLocations as $item){
            $msisdn = $item->getMsisdn();
            $result = $this->get('app.mife')->getILocatorManager()->getCurrentLocation($msisdn);

            $lat        =$result[0] ->lat;
            $lng        =$result[0] ->lon;

            //$location = array('id'=>$item->getTruck()->getId(),'name'=>$item->getName(),'lat'=>$item->getLat(),'lng'=>$item->getLng(), 'msisdn'=>$item->getTruck()->getMsisdn());
            $location = array('id'=>$item->getId(),'name'=>$item->getVehicle()->getVehicleNo(),'lat'=>floatval($lat),'lng'=>floatval($lng), 'msisdn'=>$item->getMsisdn());
            $locations[]= $location;
        }

        return new Response(json_encode($locations),200);
    }

    /**
     *
     * @Route("/home/get-single-truck-location", name="app_dashboard_get_single_truck_location")
     */
    public function getSingleTruckLocateAction(Request $request)
    {
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $truck=$em->getRepository('AppBundle:TrackingUnit')->find($request->get('id'));
        $msisdn = $truck->getMsisdn();
        $result = $this->get('app.mife')->getILocatorManager()->getCurrentLocation($msisdn);

        $lat        =$result[0] ->lat;
        $lng        =$result[0] ->lon;
        $truckLocation = $em->getRepository('AppBundle:TrackingUnitLocate')->findOneBy(array('truck'=>$truck));
        $location = array('id'=>$truck->getId(),'name'=>$truck->getVehicle()->getVehicleNo(),'lat'=>floatval($lat),'lng'=>floatval($lng), 'msisdn'=>$truck->getMsisdn());

        return new Response(json_encode($location),200);
    }

    /**
     * @Route("/home/view-single-truck-location", name="app_dashboard_view_single_truck_location")
     */
    public function viewSingleTruckLocateAction(Request $request)
    {
        // replace this example code with whatever you need
        $em = $this->getDoctrine()->getManager();
        $truck=$em->getRepository('AppBundle:TrackingUnit')->find($request->get('id'));
        $truckLocation = $em->getRepository('AppBundle:TrackingUnitLocate')->findOneBy(array('truck'=>$truck));

        $location = new \stdClass();
        $location->id = $truckLocation->getTruck()->getId();
        $location->name = $truckLocation->getName();
        $location->lat = $truckLocation->getLat();
        $location->lng = $truckLocation->getLng();

        return $this->render('backend/tracking_unit/new_map.html.twig',array(
            'page_title'=>'Map Panel',
            'location'=>$location
        ));
    }


}