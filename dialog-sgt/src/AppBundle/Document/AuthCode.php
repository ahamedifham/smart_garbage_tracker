<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/14/16
 * Time: 4:54 PM
 */

namespace AppBundle\Document;


use FOS\OAuthServerBundle\Document\AuthCode as BaseAuthCode;
use FOS\OAuthServerBundle\Model\ClientInterface;

class AuthCode extends BaseAuthCode
{
    protected $id;
    protected $client;

    public function getClient()
    {
        return $this->client;
    }

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }
}