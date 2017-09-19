<?php

/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/29/16
 * Time: 6:02 PM
 */
namespace AppBundle\Services;

use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Document\Product;
use AppBundle\Document\Coordinates;
use AppBundle\Document\RouteInfo;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\geolocation\LatLng;


class showDumpPoints
{
   private $dmdb;
    private $dm;

    /**
     * showDumpPoints constructor.
     */
    public function __construct($doctrine_mdb)
    {
        $this->dmdb= $doctrine_mdb;
        $this->dm = $doctrine_mdb->getManager();
    }

    public function dumpPoints($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);
        if(!$route){
            return 'no_dump_points';
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
            return $dumpLatLng;
        }
    }

    public function pinPoints($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        if(!$route){
            return 'no_pin_points';
        } else {
            $pinLat = array();
            $pinLng = array();
            $pinLatLng = array();

            for ($i=0; $i<sizeof($route->getPinPoint());$i++){
                array_push($pinLat, $route->getPinPoint()[$i]->getLat());
                array_push($pinLng, $route->getPinPoint()[$i]->getLng());
            }
            for($i=0; $i<sizeof($pinLat);$i++){
                $tmp = array($pinLat[$i], $pinLng[$i]);
                array_push($pinLatLng, $tmp);
            }
            return $pinLatLng;
        }

    }

    public function path($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        if(!$route){
            return 'no_path';
        } else {
            $pathLat = array();
            $pathLng = array();
            $pathLatLng = array();

            for ($i=0; $i<sizeof($route->getPath());$i++){
                array_push($pathLat, $route->getPath()[$i]->getLat());
                array_push($pathLng, $route->getPath()[$i]->getLng());
            }
            for($i=0; $i<sizeof($pathLat);$i++){
                $tmp = array($pathLat[$i], $pathLng[$i]);
                array_push($pathLatLng, $tmp);
            }
            return $pathLatLng;
        }
    }

    public function recyclingPoints($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);
        if(!$route){
            return 'no_recycling_points';
        } else {
            $recyclingLat = array();
            $recyclingLng = array();
            $recyclingLatLng = array();

            for ($i=0; $i<sizeof($route->getRecyclingPoint());$i++){
                array_push($recyclingLat, $route->getRecyclingPoint()[$i]->getLat());
                array_push($recyclingLng, $route->getRecyclingPoint()[$i]->getLng());
            }
            for($i=0; $i<sizeof($recyclingLat);$i++){
                $tmp = array($recyclingLat[$i], $recyclingLng[$i]);
                array_push($recyclingLatLng, $tmp);
            }
            return $recyclingLatLng;
        }
    }


    public function smartBinPoints($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);
        if(!$route){
            $smartBinLatLng = array();
            return $smartBinLatLng;
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
            return $smartBinLatLng;
        }
    }

    public function setNodes($id, $nodes){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);
        $nodesToSend= array();

        foreach ($nodes as $node){
            $tempLat = $node->getLat();
            $tempLng = $node->getLng();
            $tempArray = [$tempLat, $tempLng];
            $nodesToSend[] = $tempArray;
        }
        
        $route->setNodes($nodesToSend);

        $this->dm->flush();
    }
    
    public function getNodes($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);
        $nodesGet = $route->getNodes();

        $nodesGetLatLng = array();
        foreach ($nodesGet as $item) {
            $tempLatLng = new LatLng($item[0], $item[1]);
            $nodesGetLatLng[] = $tempLatLng;
        }
        return $nodesGetLatLng;
    }

    public function setAngleMin($id, $angleMin){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $route->setAngleMin($angleMin);

        $this->dm->flush();
    }

    public function getAngleMin($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $angleMin=$route->getAngleMin();

        return $angleMin;
    }

    public function setDistanceMin($id, $distanceMin){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $route->setDistanceMin($distanceMin);

        $this->dm->flush();
    }

    public function getDistanceMin($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $distanceMin=$route->getDistanceMin();

        return $distanceMin;
    }

    public function setHashAngleArray($id, $hashAngleArray){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $route->setHashAngleArray($hashAngleArray);

        $this->dm->flush();
    }

    public function getHashAngleArray($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $hashAngleArray=$route->getHashAngleArray();

        return $hashAngleArray;
    }
    
    public function setHashDistanceArray($id, $hashDistanceArray){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $route->setHashDistanceArray($hashDistanceArray);

        $this->dm->flush();
    }

    public function getHashDistanceArray($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $hashDistanceArray=$route->getHashDistanceArray();

        return $hashDistanceArray;
    }

    public function setAngleDivisor($id, $angleDivisor){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $route->setAngleDivisor($angleDivisor);

        $this->dm->flush();
    }

    public function getAngleDivisor($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $angleDivisor=$route->getAngleDivisor();

        return $angleDivisor;
    }

    public function setDistanceDivisor($id, $distanceDivisor){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $route->setDistanceDivisor($distanceDivisor);

        $this->dm->flush();
    }

    public function getDistanceDivisor($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $distanceDivisor=$route->getDistanceDivisor();

        return $distanceDivisor;
    }

    public function setAccumulateEdge($id, $accumulateEdge){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $route->setAccumulateEdge($accumulateEdge);

        $this->dm->flush();
    }

    public function getAccumulateEdge($id){
        $route= $this->dmdb->getRepository('AppBundle:RouteInfo')->find($id);

        $accumulateEdge=$route->getAccumulateEdge();

        return $accumulateEdge;
    }




}