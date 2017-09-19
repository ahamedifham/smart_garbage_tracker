<?php
/**
 * Created by PhpStorm.
 * User: umayanga
 * Date: 1/10/17
 * Time: 7:42 PM
 */

namespace AppBundle\MIFE;


class ILocator extends Base
{
    /**
     * @param $locatorId
     * @return mixed
     */
    public function getCurrentLocation($locatorId){
        $response=null;
        $decodedResponse=null;
        $tryCount=0;

        do{
            $tryCount++;
            $response = $this->curlSendSingle($this->getApiFromAlias('ilocator_get_current_location').http_build_query(array('number'=>$locatorId)),
                array(),
                array('Authorization: Bearer '.$this->redis->get('access_token'),'Content-Type: application/x-www-form-urlencoded','GET')
            );
            $isError = $this->reAuthorizeIfError($response);
        }while($tryCount <= 3 && $isError);

        return json_decode($response);

//        while($tryCount<3){
//            $response = $this->curlSendSingle($this->getApiFromAlias('ilocator_get_current_location').http_build_query(array('number'=>$locatorId)),
//                array(),
//                array('Authorization: Bearer '.$this->redis->get('access_token'),'GET')
//            );
//
//            $decodedResponse=json_decode($response);
//
//            if (!is_null($decodedResponse)) {
//                if (property_exists($decodedResponse[0], 'number')) {     //if retrieve successful
//                    $tryCount=0;
//                    return $decodedResponse;
//                } else {
//                    $result=$this->reAuthorizeIfError();        //reauthorize
//                    $tryCount++;
//                }
//            }
//            else{      //if error occurred
//
//                $result=$this->reAuthorizeIfError();        //reauthorize
//                $tryCount++;
//
//            }
//
//        };
//
//        return null;

    }
}