<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 9/14/16
 * Time: 4:56 PM
 */

namespace AppBundle\Document;


use FOS\OAuthServerBundle\Document\RefreshToken as BaseRefreshToken;
use FOS\OAuthServerBundle\Model\ClientInterface;

class RefreshToken extends BaseRefreshToken
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