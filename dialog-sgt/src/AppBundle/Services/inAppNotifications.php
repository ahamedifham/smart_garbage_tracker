<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 12/8/16
 * Time: 2:20 PM
 */

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;



class inAppNotifications
{
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    private function getRepository($class)
    {
        return $this->em->getRepository('AppBundle:'.$class);
    }

    private function sendCurlRequest($url,$data)
    {
        $data_string = json_encode($data);

        // curl method to send request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: key=AIzaSyA5vQklUIA-7i-HFj0UgUWoIjXvvEKhH1U',
        ));
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

    public function sendInAppNotification($gcm, $title, $message){
        $responseObj = array(
            'to'=>$gcm,
            'data'=>array(
                'title'=>$title,
                'message'=>$message,
                'style' => "Inbox",
                'notId' => 1
            )
        );
        $response = $this->sendCurlRequest('https://fcm.googleapis.com/fcm/send',$responseObj);
        return $response;
    }
}