<?php
/**
 * Created by PhpStorm.
 * User: umayanga
 * Date: 1/10/17
 * Time: 8:04 PM
 */

namespace AppBundle\MIFE;
use Symfony\Component\Yaml\Yaml;

class Base
{
    protected $doctrineORM;
    protected $doctrineODM;
    protected $redis;
    protected $logger;

    private $authCount=0;
    protected $config;

    public function __construct($doctrineORM,$doctrineODM,$redis,$logger)
    {
        $this->doctrineORM = $doctrineORM;
        $this->doctrineODM = $doctrineODM;
        $this->redis = $redis;
        $this->logger = $logger;

        // read the YML and load the configs here
        $this->config = Yaml::parse(file_get_contents( __DIR__.'/config/config.yml'));
    }

    protected function getApiFromAlias($alias){
        return $this->config['apis'][$alias];
    }

    protected function curlSendSingle($url,$parameters,$headers=array(),$method='POST'){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        if($method == 'POST')
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            if(!count($parameters) > 0 || is_string($parameters))
                curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);  //Post Fields
        }elseif ($method=='GET'){
            curl_setopt($ch, CURLOPT_POST, 0);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

    protected function curlSendSingle2($url,$parameters,$headers=array(),$method='POST'){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        if($method == 'POST')
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            if(!count($parameters) > 0)
                curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);  //Post Fields
        }elseif ($method=='GET'){
            curl_setopt($ch, CURLOPT_POST, 0);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }
    /**
     * @param $response , response from any API call
     */
    protected function reAuthorizeIfError($response){
        $responseObj = json_decode($response);
        $this->authCount++;
        if($responseObj == null || isset($responseObj->error)) // TODO MIFE responses are not proper in error
        {
            // comes here when authorization error else return false
            // check if a token exists,
            $access_token = $this->redis->get('access_token');
            $refresh_token= $this->redis->get('refresh_token');

            // a) if not get a token and $authAttempts = 0
            if (is_null($access_token) || is_null($refresh_token)) {
                $tokenResponse = $this->curlSendSingle($this->getApiFromAlias('token_create') . http_build_query(array('username' => $this->config['username'], 'password' => $this->config['password'])),
                    array(),
                    array('Authorization: Basic ' . $this->config['authorization_code'],'Content-Type: application/x-www-form-urlencoded')
                );


            } // b) if exist call refresh token and $authAttempts++
            else {
                $tokenResponse = $this->curlSendSingle($this->getApiFromAlias('token_refresh') .http_build_query(array('refresh_token' => $refresh_token)),
                    array(),
                    array('Authorization: Basic ' . $this->config['authorization_code'],'Content-Type: application/x-www-form-urlencoded')
                );
            }

            $tokenResponseObj=json_decode($tokenResponse);

            if (property_exists($tokenResponseObj,'access_token') && property_exists($tokenResponseObj,'refresh_token')) {
                // store the new token to the database in case (a) and (b)

                $this->redis->set   ('access_token',    $tokenResponseObj->access_token);
                $this->redis->expire('access_token',    $tokenResponseObj->expires_in-100);//expire time in seconds
                $this->redis->set   ('refresh_token',   $tokenResponseObj->refresh_token);
                $this->redis->expire('refresh_token',   $tokenResponseObj->expires_in-100);

            }else{ //error creating or refreshing token
                if($this->authCount >= 3)
                {
                    $this->logger->error('Unable to authorize MIFE');
                    return false;
                }
                else{
                    $this->reAuthorizeIfError($response);
                }
            }
            // else
            $this->authCount = 0;
            return true;//auth success
        }
        $this->authCount = 0;
        return false;
        
    }


}