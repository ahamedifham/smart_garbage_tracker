<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/8/16
 * Time: 8:33 PM
 */

namespace AppBundle\Controller\Mobile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class TrackingUnitLocateAPIController extends APIBaseController
{
    /**
     * @Route("/tracking_unit_locate/get_data", name="app_tracking_unit_locate_get_data")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getDataAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $trackingUnitRepo = $em->getRepository('AppBundle:TrackingUnit');
        $trackingUnitLocateRepo = $em->getRepository('AppBundle:TrackingUnitLocate');
        $ideabizAccessCodeRepo = $em->getRepository('AppBundle:IdeabizAccessCode');

        $ideabizAccessCode = $ideabizAccessCodeRepo->profile('MAP_CODE');
        $accessCode = $ideabizAccessCode->getValue();
        //var_dump($accessCode);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://ideabiz.lk/apicall/iLocate/v1/getCurrentLocation/715324145?number=766117164");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = [
            'Authorization: Bearer '.$accessCode,
            'Content-Type: application/x-www-form-urlencoded',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $data = json_decode($server_output);

        var_dump($data[0]->number);



        $trackingUnit = $trackingUnitRepo->getUnit(intval($data[0]->number));
        $trackingUnitLocate = $trackingUnitLocateRepo->getUnitLocate($trackingUnit);


        $trackingUnitLocate->setName($data[0]->name);
        $trackingUnitLocate->setLat(floatval($data[0]->lat));
        $trackingUnitLocate->setLng(floatval($data[0]->lon));

        $errors= $em->flush();
        var_dump($trackingUnitLocate);
        $responseObj = (object)array(
            'status' => count($errors)? false:true,
            'errors' => array(),
        );

        var_dump($responseObj);

        return $this->handleResponse($responseObj);
    }
}