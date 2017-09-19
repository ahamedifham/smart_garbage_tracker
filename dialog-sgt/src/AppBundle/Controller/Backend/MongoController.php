<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/19/16
 * Time: 10:37 AM
 */

namespace AppBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CommonUser;
use AppBundle\Form\Backend\CommonUser as CommonUserForm;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Document\Product;
use AppBundle\Document\Coordinates;
use AppBundle\Document\RouteInfo;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ODM\MongoDB\DocumentManager;
use AppBundle\RepositoryDOM\RouteInfoRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM;



class MongoController extends BaseController
{

    /**
     * @Route("mongo/create", name="app_mongo_create")
     */
    public function createAction(){
        $product= new Product();
        $product->setName('A Foo Bar');
        $product->setPrice('20');

        $dm= $this->get('doctrine_mongodb')->getManager();
        $dm->persist($product);
        $dm->flush();

        return new Response('Created product id'.$product->getId());
    }

    /**
     * @Route("mongo/fetch/{id}", name="app_mongo_fetch")
     */
    public function showAction($id){
        $product= $this->get('doctrine_mongodb')
            ->getRepository('AppBundle:Product')
            ->find($id);

        if (!$product){

            throw $this->createNotFoundException('No product found for id '.$id);        }

        return new Response('Product Name is '.$product->getName());
    }

    /**
     * @Route("mongo/update/{id}", name="app_mongo_update")
     */
    public function updateAction($id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $product = $dm->getRepository('AppBundle:Product')->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }
        $product->setName('Boo Bar');
        $dm->flush();
        return new Response('Product is updated');
    }

    /**
     * @Route("mongo/show/all", name="app_mongo_show_all")
     */
    public function showAllAction(){
        $products = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('AppBundle:Product')
            ->findAllOrderedByName();
        var_dump($products);
        exit();

        return new Response('Products are '.$products[1]->getName());
    }


    /**
     * @Route("mongo/route/create/loop", name="app_mongo_create_loop")
     */
    public function createRouteLoopAction(Request $request){
        $routeInfo= new RouteInfo();
        $routeInfo->setIsEdited(true);
        $routeInfo->setStatus(true);

        $dm= $this->get('doctrine_mongodb')->getManager();


        $latitudes= $request->get('sendLat');
        $longitudes= $request->get('sendLng');
        $pinPointLat= $request->get('pinLat');
        $pinPointLng= $request->get('pinLng');
        $dumpPointLat= $request->get('dumpLat');
        $dumpPointLng= $request->get('dumpLng');
        $recyclingPointLat = $request->get('recyclingLat');
        $recyclingPointLng= $request->get('recyclingLng');

        for($i=0;$i<sizeof($latitudes);$i++){
            $coordinates= new Coordinates();
            $coordinatesPin = new Coordinates();
            $coordinatesDump= new Coordinates();
            $coordinatesRecycling= new Coordinates();
            $coordinates->setLat($latitudes[$i]);
            $coordinates->setLng($longitudes[$i]);
            if($i<sizeof($pinPointLng)){
                $coordinatesPin->setLat($pinPointLat[$i]);
                $coordinatesPin->setLng($pinPointLng[$i]);
                $routeInfo->addPinPoint($coordinatesPin);
            }
            if ($i<sizeof($dumpPointLat)){
                $coordinatesDump->setLng($dumpPointLng[$i]);
                $coordinatesDump->setLat($dumpPointLat[$i]);
                $routeInfo->addDumpPoint($coordinatesDump);
            }
            if ($i<sizeof($recyclingPointLat)){
                $coordinatesRecycling->setLng($recyclingPointLng[$i]);
                $coordinatesRecycling->setLat($recyclingPointLat[$i]);
                $routeInfo->addRecyclingPoint($coordinatesRecycling);
            }

            $routeInfo->addPath($coordinates);
            //$routeInfo->addResampledPath($coordinates);

        }
        $dm->persist($routeInfo);
        $dm->flush();
        //$routeInfo->addPinPoint($coordinates);
        return new Response(json_encode($routeInfo->getId()));
    }    
    
    /**
     * @Route("mongo/smart_bin/create/loop", name="app_mongo_create_smart_bin")
     */
    public function createSmartBinAction(Request $request){
//        $routeInfo= new RouteInfo();
//        $routeInfo->setIsEdited(true);
//        $routeInfo->setStatus(true);
//
//        $dm= $this->get('doctrine_mongodb')->getManager();
//
//
//        $latitudes= $request->get('sendLat');
//        $longitudes= $request->get('sendLng');
//        $pinPointLat= $request->get('pinLat');
//        $pinPointLng= $request->get('pinLng');
//        $dumpPointLat= $request->get('dumpLat');
//        $dumpPointLng= $request->get('dumpLng');
//        $recyclingPointLat = $request->get('recyclingLat');
//        $recyclingPointLng= $request->get('recyclingLng');
//
//        for($i=0;$i<sizeof($latitudes);$i++){
//            $coordinates= new Coordinates();
//            $coordinatesPin = new Coordinates();
//            $coordinatesDump= new Coordinates();
//            $coordinatesRecycling= new Coordinates();
//            $coordinates->setLat($latitudes[$i]);
//            $coordinates->setLng($longitudes[$i]);
//            if($i<sizeof($pinPointLng)){
//                $coordinatesPin->setLat($pinPointLat[$i]);
//                $coordinatesPin->setLng($pinPointLng[$i]);
//                $routeInfo->addPinPoint($coordinatesPin);
//            }
//            if ($i<sizeof($dumpPointLat)){
//                $coordinatesDump->setLng($dumpPointLng[$i]);
//                $coordinatesDump->setLat($dumpPointLat[$i]);
//                $routeInfo->addDumpPoint($coordinatesDump);
//            }
//            if ($i<sizeof($recyclingPointLat)){
//                $coordinatesRecycling->setLng($recyclingPointLng[$i]);
//                $coordinatesRecycling->setLat($recyclingPointLat[$i]);
//                $routeInfo->addRecyclingPoint($coordinatesRecycling);
//            }
//
//            $routeInfo->addPath($coordinates);
//            //$routeInfo->addResampledPath($coordinates);
//
//        }
//        $dm->persist($routeInfo);
//        $dm->flush();
//        return new Response(json_encode($routeInfo->getId()));
        

        $routeId = $request->get('routeId');
        $smartBinPointLat = $request->get('smartBinLat');
        $smartBinPointLng = $request->get('smartBinLng');
        $id = $request->get('id');
//
//        $smartBinPointLatLng = array();
//        $smartBinPointLatLng[]=$smartBinPointLat;
//        $smartBinPointLatLng[]=$smartBinPointLng;
//
//        $dm = $this->get('doctrine_mongodb')->getManager();
//        $routeInfo = $dm->getRepository('AppBundle:RouteInfo')->find($routeId);
//
//        $smartBinPoints = $this->get('app.mongodb_dump_points')->smartBinPoints($routeId);
//
//
//        $smartBinPoints[] = $smartBinPointLatLng;
//
//        $smartBinPointLats =array();
//        $smartBinPointLngs = array();
//        for ($i=0 ; $i<sizeof($smartBinPoints); $i++){
//            $smartBinPointLats[] = $smartBinPoints[$i][0];
//            $smartBinPointLngs[] = $smartBinPoints[$i][1];
//        }
//
//
//        $routeInfo->removeAllSmartBinPoints();
//
//
//        for ($i=0; $i<sizeof($smartBinPointLats); $i++){
//            $coordinatesDump= new Coordinates();
//            $coordinatesDump->setLat(floatval($smartBinPointLats[$i]));
//            $coordinatesDump->setLng(floatval($smartBinPointLngs[$i]));
//            $routeInfo->addSmartBinPoint($coordinatesDump);
//        }
//
//
//        $dm->persist($routeInfo);
//        $dm->flush();

        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:SmartBin');
        $smartBin = $repo->getSmartBinFromId($id);

        $smartBin->setSmartBinPointLat(floatval($smartBinPointLat));
        $smartBin->setSmartBinPointLng(floatval($smartBinPointLng));

        $em->flush();
        return new Response(json_encode(true));

    }

    /**
     * @Route("/mongo/route/fetch", name = "app_mongo_fetch_route")
     * @param Request $request
     * @return Response
     */

    public function showRoute(Request $request){
        //$id= '57e3bc608589a7770c8b4568';
       // $id= '57e4ba588589a7a90d8b4567';
        $id= $request->get('id');
        $routeId= $this->get('doctrine_mongodb')
            ->getRepository('AppBundle:RouteInfo')
            ->find($id);
        if(!$routeId){
            return new Response(json_encode('no_result'));
        }else {
            $pathLat = array();
            $pathLng = array();
            $pinLat = array();
            $pinLng = array();
            $pinPoint = array();

            for ($i = 0; $i < sizeof($routeId->getPath()); $i++) {
                array_push($pathLat, $routeId->getPath()[$i]->getLat());
                array_push($pathLng, $routeId->getPath()[$i]->getLng());
            }
            for ($i = 0; $i < sizeof($routeId->getPinPoint()); $i++) {
                array_push($pinLat, $routeId->getPinPoint()[$i]->getLat());
                array_push($pinLng, $routeId->getPinPoint()[$i]->getLng());
            }

            for ($i = 0; $i < sizeof($pinLat); $i++) {
                $tmp = array($pinLat[$i], $pinLng[$i]);
                array_push($pinPoint, $tmp);
            }
            return new Response(json_encode($pinPoint));
        }
    }

    /**
     * @Route("mongo/dump/fetch", name = "app_mongo_fetch_dump")
     * @param Request $request
     * @return Response
     */
    public function showDumpPoints(Request $request){
        $id= $request->get('id');
        $route= $this->get('doctrine_mongodb')
            ->getRepository('AppBundle:RouteInfo')
            ->find($id);
        if(!$route){
            return new Response(json_encode('no_dump_points'));
        } else {
            $dumpLat = array();
            $dumpLng = array();
            $dumpLatLng = array();

            for ($i=0; $i<sizeof($route->getDumpPoint());$i++){
                array_push($dumpLat, $route->getDumpPoint()[$i]->getLat());
                array_push($dumpLng, $route->getDumpPoint()[$i]->getLng());
            }
            for($i=0; $i<sizeof($dumpLat);$i++){
                $tmp = array($dumpLat[$i], $dumpLng[$i]);
                array_push($dumpLatLng, $tmp);

            }
            return new Response(json_encode($dumpLatLng));
        }
    }

    /**
     * @Route("mongo/smart_bin/fetch", name = "app_mongo_fetch_smart_bin")
     * @param Request $request
     * @return Response
     */
    public function showSmartBinPoints(Request $request){
        $id= $request->get('id');
        $route= $this->get('doctrine_mongodb')
            ->getRepository('AppBundle:RouteInfo')
            ->find($id);
        if(!$route){
            return new Response(json_encode('no_smart_bin_points'));
        } else {
            $smartBinLat = array();
            $smartBinLng = array();
            $smartBinLatLng = array();

            for ($i=0; $i<sizeof($route->getSmartBinPoint());$i++){
                array_push($smartBinLat, $route->getSmartBinPoint()[$i]->getLat());
                array_push($smartBinLng, $route->getSmartBinPoint()[$i]->getLng());
            }
            for($i=0; $i<sizeof($smartBinLat);$i++){
                $tmp = array($smartBinLat[$i], $smartBinLng[$i]);
                array_push($smartBinLatLng, $tmp);

            }
            return new Response(json_encode($smartBinLatLng));
        }
    }

    /**
     * @Route("mongo/smart_bin/get_bin", name = "app_mongo_get_smart_bin")
     * @param Request $request
     * @return Response
     */
    public function getSmartBinAction(Request $request){
        $id= $request->get('id');

        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:SmartBin');
        $smartBin = $repo->getSmartBinFromId($id);

        $smartBinLat = $smartBin->getSmartBinPointLat();
        $smartBinLng = $smartBin->getSmartBinPointLng();

        if($smartBinLat==null){
            $smartBinLatLng = 'no_smart_bin_points';
        }else{
            $smartBinLatLng = array();
            $tmp = array($smartBinLat, $smartBinLng);
            array_push($smartBinLatLng, $tmp);
        }

        return new Response(json_encode($smartBinLatLng));

    }

    /**
     * @Route("mongo/recycling/fetch", name="app_mongo_fetch_recycling")
     * @param Request $request
     * @return Response
     */
    public function showRecyclingPoints(Request $request){
        $id= $request->get('id');
        $route= $this->get('doctrine_mongodb')
            ->getRepository('AppBundle:RouteInfo')
            ->find($id);
        if(!$route){
            return new Response(json_encode('no_recycling_points'));
        } else {
            $recyclingLat= array();
            $recyclingLng= array();
            $recyclingLatLng= array();

            for ($i=0; $i<sizeof($route->getRecyclingPoint()); $i++){
                array_push($recyclingLat, $route->getRecyclingPoint()[$i]->getLat());
                array_push($recyclingLng, $route->getRecyclingPoint()[$i]->getLng());
            }
            for ($i=0; $i<sizeof($recyclingLat); $i++){
                $tmp = array($recyclingLat[$i], $recyclingLng[$i]);
                array_push($recyclingLatLng, $tmp);
            }
            return new Response(json_encode($recyclingLatLng));
        }
    }

    /**
     * @Route("mongo/path/fetch", name="app_mongo_fetch_path")
     * @param Request $request
     * @return Response
     */
    public function showPath(Request $request){
        $id = $request->get('id');
        $route= $this->get('doctrine_mongodb')
            ->getRepository('AppBundle:RouteInfo')
            ->find($id);
        $pathLat = array();
        $pathLng = array();
        $pathLatLng = array();
        
        if(!$route){
            return new Response(json_encode('no_path'));
            
        }else {

            for ($i = 0; $i < sizeof($route->getPath()); $i++) {
                array_push($pathLat, $route->getPath()[$i]->getLat());
                array_push($pathLng, $route->getPath()[$i]->getLng());
            }

            for ($i = 0; $i < sizeof($pathLat); $i++) {
                $tmp = array($pathLat[$i], $pathLng[$i]);
                array_push($pathLatLng, $tmp);
            }

            return new Response(json_encode($pathLatLng));
        }
    }

    /**
     * @Route("mongo/route/update" , name= "app_mongo_route_update")
     */
    public function updateRouteAction(Request $request){
        $id= $request->get('id');
        $latitudes= $request->get('sendLat');
        $longitudes= $request->get('sendLng');
        $pinPointLat= $request->get('pinLat');
        $pinPointLng= $request->get('pinLng');
        $dumpPointLat= $request->get('dumpLat');
        $dumpPointLng= $request->get('dumpLng');
        $recyclingPointLat = $request->get('recyclingLat');
        $recyclingPointLng= $request->get('recyclingLng');


        $dm = $this->get('doctrine_mongodb')->getManager();
        $routeInfo = $dm->getRepository('AppBundle:RouteInfo')->find($id);


        //Updates

        $routeInfo->removeAllPinPoints();
        $routeInfo->removeAllPath();
        $routeInfo->removeAllDumpPoints();
        $routeInfo->removeAllRecyclingPoints();
        $routeInfo->removeAllSmartBinPoints();

        for($i=0;$i<sizeof($latitudes);$i++){
            $coordinates= new Coordinates();
            $coordinates->setLat($latitudes[$i]);
            $coordinates->setLng($longitudes[$i]);
            if($i<sizeof($pinPointLng)){
                $coordinatesPin = new Coordinates();
                $coordinatesPin->setLat($pinPointLat[$i]);
                $coordinatesPin->setLng($pinPointLng[$i]);
                $routeInfo->addPinPoint($coordinatesPin);
            }
            if ($i<sizeof($dumpPointLat)){
                $coordinatesDump= new Coordinates();
                $coordinatesDump->setLat($dumpPointLat[$i]);
                $coordinatesDump->setLng($dumpPointLng[$i]);
                $routeInfo->addDumpPoint($coordinatesDump);
            }
            if ($i<sizeof($recyclingPointLat)){
                $coordinatesRecycling= new Coordinates();
                $coordinatesRecycling->setLng($recyclingPointLng[$i]);
                $coordinatesRecycling->setLat($recyclingPointLat[$i]);
                $routeInfo->addRecyclingPoint($coordinatesRecycling);
            }

            $routeInfo->addPath($coordinates);
            //$routeInfo->addResampledPath($coordinates);

        }
        $dm->flush();



//        $dm->persist($routeInfo);

        return new Response('Created path id'.$routeInfo->getId()); //TODO proper json response with handleResponseMethod
    }

    /**
     * @Route("mongo/route/remove", name="app_mongo_route_delete")
     */
    public function removeRoute(){

        $id='57e50dbd8589a7a60d8b4582';

        $dm = $this->get('doctrine_mongodb')->getManager();
        $routeId = $dm->getRepository('AppBundle:RouteInfo')->find($id);

        foreach ($routeId->getPath() as $waypoint){
            $routeId->removePath($waypoint);
        }
        foreach ($routeId->getPinPoint() as $pinpoint){
            $routeId->removePinPoint($pinpoint);
        }

        $dm->persist($routeId);
        $dm->flush();
        var_dump($routeId);
        exit();

    }

    /**
     * @Route("mongo/route/get_scheduled_routes", name="app_mongo_route_get_scheduled_routes")
     */
    public function getScheduledRoutes(){
        $todayDate = date("Y-m-d");
        $em = $this->getDoctrine()->getManager();
        $scheduleRepo = $em->getRepository('AppBundle:Schedule');
        $schedules = $scheduleRepo->getSchedules($todayDate);
       // var_dump(sizeof($schedules));
        $routeIds= array();
        foreach ($schedules as $schedule){
            $tempId = $schedule->getId();
            $tempSchedule = $scheduleRepo->profile($tempId);
            $route= $tempSchedule->getRoute();
            $routeIds[] = $route->getRouteId();
        }
        //var_dump($routeIds);
        return new Response(json_encode($routeIds));
    }

    /**
     * @Route("/mongo/route/fetch_path", name = "app_mongo_fetch_route_path")
     * @param Request $request
     * @return Response
     */

    public function showRoutePath(Request $request){
        //$id= '57e3bc608589a7770c8b4568';
        // $id= '57e4ba588589a7a90d8b4567';
        $id= $request->get('id');
        $routeId= $this->get('doctrine_mongodb')
            ->getRepository('AppBundle:RouteInfo')
            ->find($id);
        if(!$routeId){
            return new Response(json_encode('no_result'));
        }else {
            $pathLat = array();
            $pathLng = array();
            $pinLat = array();
            $pinLng = array();
            $pinPoint = array();
            $pathPoint = array();


            for ($i = 0; $i < sizeof($routeId->getPath()); $i++) {
                array_push($pathLat, $routeId->getPath()[$i]->getLat());
                array_push($pathLng, $routeId->getPath()[$i]->getLng());
            }
//            for ($i = 0; $i < sizeof($routeId->getPinPoint()); $i++) {
//                array_push($pinLat, $routeId->getPinPoint()[$i]->getLat());
//                array_push($pinLng, $routeId->getPinPoint()[$i]->getLng());
//            }

            for ($i = 0; $i < sizeof($pathLat); $i++) {
                $tmp = array($pathLat[$i], $pathLng[$i]);
                array_push($pathPoint, $tmp);
            }
            return new Response(json_encode($pathPoint));
        }
    }
}