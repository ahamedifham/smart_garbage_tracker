<?php
/**
 * Created by PhpStorm.
 * User: umayanga
 * Date: 1/12/17
 * Time: 5:20 PM
 */

namespace AppBundle\MIFE;


class SMS extends Base
{
    /**
     * @param $addresses
     * @param $message
     * @return mixed
     */
    public function sendSMS($addresses,$message)
    {
        $this->logger->info('came here -1');

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

        $this->logger->info('came here 0');
        $response=null;
        $decodedResponse=null;
        $tryCount=0;

        do{
            $this->logger->info('came here 1');
            $tryCount++;
            $response = $this->curlSendSingle($this->getApiFromAlias('sms_send'),
                json_encode($data),
                array('Authorization: Bearer '.$this->redis->get('access_token'),'Accept: application/json','Content-Type: application/json')
            );
            $this->logger->info($response);
            $this->logger->info('came here 2');
            $isError = $this->reAuthorizeIfError($response);
            $this->logger->info('came here 3');
        }while($tryCount <= 3 && $isError);

        return json_decode($response);

    }
}