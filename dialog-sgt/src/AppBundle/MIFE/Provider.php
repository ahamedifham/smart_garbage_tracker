<?php

namespace AppBundle\MIFE;
/**
 * Created by PhpStorm.
 * User: umayanga
 * Date: 1/10/17
 * Time: 7:40 PM
 */
class Provider
{
    private $doctrineORM;
    private $doctrineODM;
    private $redis;
    private $logger;

    public function __construct($doctrineORM,$doctrineODM,$redis,$logger)
    {
        $this->doctrineORM  = $doctrineORM;
        $this->doctrineODM  = $doctrineODM;
        $this->redis        = $redis;
        $this->logger       = $logger;

        $this->authAttempts = 0;
    }

    public function getILocatorManager(){
        return new ILocator($this->doctrineORM,$this->doctrineODM,$this->redis,$this->logger);
    }

    public function getSMSManager(){
        return new SMS($this->doctrineORM,$this->doctrineODM,$this->redis,$this->logger);
    }


}