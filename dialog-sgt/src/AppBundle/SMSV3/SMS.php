<?php
/**
 * Created by PhpStorm.
 * User: Charith
 * Date: 11/12/2016
 * Time: 4:54 PM
 */

namespace AppBundle\SMSV3;


use Doctrine\ORM\EntityManager;

class SMS
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
        // find the access code from the database. imaging the access code is updated from another process.
        $accessCode = $this->getRepository('IdeabizAccessCode')->findOneBy(array('metaCode'=>'MAP_CODE'))->getValue();
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
            'Authorization: Bearer '.$accessCode,
            'Accept: application/json'
        ));
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

    public function sendSMS($addresses,$message)
    {
        // create request body
        $data = new \stdClass();
        $data->outboundSMSMessageRequest = array(
            'address'=>$addresses,
            'senderAddress'=>'tel:94768887711',
            //'senderAddress'=>'tel:887711',
            'outboundSMSTextMessage'=>array(
                'message'=>$message
            ),
            'clientCorrelator'=>'123456',
            'receiptRequest'=>array(
                'notifyURL'=>'http://128.199.174.220:1080/sms/report',
                'callbackData'=>'some-data-useful-to-the-requester'
            ),
            'senderName'=>''
        );

        // send request calling method
        $response = $this->sendCurlRequest('https://ideabiz.lk/apicall/smsmessaging/v3/outbound/94768887711/requests',$data);
        return $response;
    }
}