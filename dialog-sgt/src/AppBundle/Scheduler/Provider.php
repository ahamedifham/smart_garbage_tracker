<?php
/**
 * Created by PhpStorm.
 * User: umayanga
 * Date: 1/10/17
 * Time: 11:33 AM
 */

namespace AppBundle\Scheduler;


class Provider
{
    private $doctrineORM;
    private $redis;

    private $checkSchedule;

    public function __construct($doctrineORM,$redis)
    {
        $this->doctrineORM = $doctrineORM;
        $this->redis = $redis;

        $this->checkSchedule = new CheckSchedule($doctrineORM,$redis);

    }

    public function isInSchedule($truckId,$dateTime){
        return $this->checkSchedule->isInSchedule($truckId,$dateTime);
    }

    public function getRouteIdFromSchedule($Msisdn){
        return $this->checkSchedule->getRouteIdFromSchedule($Msisdn);
    }
}