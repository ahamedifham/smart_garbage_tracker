<?php
/**
 * Created by PhpStorm.
 * User: umayanga
 * Date: 1/10/17
 * Time: 10:46 AM
 */

namespace AppBundle\Scheduler;

use Symfony\Component\Validator\Constraints;

class CheckSchedule
{
    private $doctrineORM;
    private $redis;

    public function __construct($doctrineORM,$redis)
    {
        $this->doctrineORM = $doctrineORM;
        $this->redis = $redis;
    }

    /**
     * This function checks whether a truck given by track unit id is in a schedule at a given time of a given day
     * and returns the schedule info in a array if any available
     * @param $truckId
     * @param $dateTime
     * @return bool
     */
    public function isInSchedule($Msisdn,\DateTime $dateTime){

        $em = $this->doctrineORM;
        $redis = $this->redis;

        $scheduleRepo= $em->getRepository('AppBundle:Schedule');
        $vehicleRepo= $em->getRepository('AppBundle:Vehicle');
        $trackingUnitRepo= $em->getRepository('AppBundle:TrackingUnit');

        //$vehicle=$vehicleRepo->find($truckId);
        $trackingUnits=$trackingUnitRepo->getTrackingUnitFromMsisdn($Msisdn);

        foreach ($trackingUnits as $trackingUnit) {

            $date = $dateTime->format('Y-m-d');
            $time = strtotime($dateTime->format('H:i:s'));

            $todayDate = date("Y-m-d");
            $schedules = $scheduleRepo->getSchedulesWithTrackingUnit($todayDate, $trackingUnit);

            if (sizeof($schedules) == 0) {
                return false;
            } else {
                return true;
            }
        }

            //From Here onward the code is being commented because the relavent quaery is written in repository

//            //first check in non-repeating schedules for given date and time
//            $nonRepeatingSchedules = $scheduleRepo->findBy(array('repeat1' => 0, 'date' => $dateTime, 'truck'=>$trackingUnit));
//
//            //      check if given time is within the time range of each schedule
//
//            foreach ($nonRepeatingSchedules as $schedule) {
//                $startTime = strtotime($schedule->getStartTime()->format('H:i:s'));
//                $endTime = strtotime($schedule->getEndTime()->format('H:i:s'));
//
//                //check if time is within given period
//                //if any record found immediately return info
//                if ($time > $startTime && $time < $endTime) {
//                    return $schedule->getName();
//                }
//            }
//            //if no record is found in non-repeating schedules
//            //next check repeating schedules
//            $repeatingSchedules = $scheduleRepo->findBy(array('repeat1' => 1, 'truck'=>$trackingUnit));
//
//            //     get the long day of the given date
//            $day = $dateTime->format('l');
//
//            foreach ($repeatingSchedules as $schedule) {
//                $startTime = strtotime($schedule->getStartTime()->format('H:i:s'));
//                $endTime = strtotime($schedule->getEndTime()->format('H:i:s'));
//                $fixedDate = $schedule->getDate()->format('Y-m-d');
//                $weekDays = $schedule->getWeekday();
//
//                //check if time is within given period
//                //      also check if date the schedule was fixed is before the given date
//
//                if ($time > $startTime && $time < $endTime && $fixedDate <= $date) {
//
//                    foreach ($weekDays as $weekDay) {
//                        if ($weekDay->getDayName() == $day) {
//                            return $schedule->getName();
//                        }
//                    }
//                }
//            }
//        }
//
//        return false;

    }

    public function getRouteIdFromSchedule($Msisdn){
        $em = $this->doctrineORM;
        $scheduleRepo= $em->getRepository('AppBundle:Schedule');
        $trackingUnitRepo= $em->getRepository('AppBundle:TrackingUnit');
        $trackingUnits=$trackingUnitRepo->getTrackingUnitFromMsisdn($Msisdn);
        foreach ($trackingUnits as $trackingUnit) {

            $todayDate = date("Y-m-d");
            $schedules = $scheduleRepo->getSchedulesWithTrackingUnit($todayDate, $trackingUnit);

            if (sizeof($schedules) == 0) {
                return false;
            } else {
                $routeId = $schedules[0]->getRoute()->getId();
                return $routeId;
            }
        }


    }
}