<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/8/16
 * Time: 12:55 PM
 */

namespace AppBundle\Controller\Mobile;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class APIBaseController extends BaseController
{

    /**
     * @param $data
     * @return Response
     */
    protected function handleResponse($data){
        $response =  new Response(json_encode($data));
        $responseHeaders = $response->headers;
        $responseHeaders->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
//        $responseHeaders->set('content-type', 'application/json');
//        $responseHeaders->set('accept', 'application/json');
//        $responseHeaders->set('Access-Control-Allow-Origin', '*');
        $responseHeaders->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        return $response;
    }

    /**
     * @return mixed
     */
    protected function handleRequest(){
        $request = $this->get('request');
        $data = json_decode($request->getContent());
       
        return $data;
    }

    /**
     * @Route("/route/check")
     */
    public function checkForRoutesAndCollectionPoints(){

        return $this->render('backend/mobileAPI/list.html.twig');
    }
}