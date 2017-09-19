<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/23/16
 * Time: 12:44 PM
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\AlertAlgorithm\Algorithm;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;


class GenerateTimeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        // ...
        $this
            // the name of the command (the part after "app/console")
            ->setName('app:set-time')

            // the short description shown while running "php app/console list"
            ->setDescription('Set Arrival Times')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to create users...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $trackingUnitRepo = $em->getRepository('AppBundle:TrackingUnit');
        $trackingUnitLocateRepo = $em->getRepository('AppBundle:TrackingUnitLocate');
        $routeRepo= $em->getRepository('AppBundle:Route');
        $commonUserRepo= $em->getRepository('AppBundle:CommonUser');


        //$trackingUnits = $trackingUnitRepo->findBy(array('status'=>1));
        $trackingUnitLocateTemp = $trackingUnitLocateRepo->profile(3);
        $lat = $trackingUnitLocateTemp->getLat();
        $lng = $trackingUnitLocateTemp->getLng();

        //var_dump($lat, $lng);

        $count = $routeRepo->count();

        for($i=1; $i<$count;$i++){
            var_dump('Check 1.'.$i);
            $route = $routeRepo->profile($i);
            $routeId = $route->getRouteId();

            $nodes = $this->getContainer()->get('app.mongodb_dump_points')->getNodes($routeId);
            $angleMin = $this->getContainer()->get('app.mongodb_dump_points')->getAngleMin($routeId);
            $distanceMin = $this->getContainer()->get('app.mongodb_dump_points')->getDistanceMin($routeId);
            $hashAngleArray = $this->getContainer()->get('app.mongodb_dump_points')->getHashAngleArray($routeId);
            $hashDistanceArray = $this->getContainer()->get('app.mongodb_dump_points')->getHashDistanceArray($routeId);
            $angleDivisor = $this->getContainer()->get('app.mongodb_dump_points')->getAngleDivisor($routeId);
            $distanceDivisor = $this->getContainer()->get('app.mongodb_dump_points')->getDistanceDivisor($routeId);
            $accumulateEdge = $this->getContainer()->get('app.mongodb_dump_points')->getAccumulateEdge($routeId);

            $angleDivisor = floatval($angleDivisor);
            $distanceDivisor=floatval($distanceDivisor);


//            $lat= 6.9273640932202;
//            $lng = 79.996716231108;
            
//            $lat= 6.8722117;
//            $lng = 79.86642;

            $routeIntId = $route->getId();
            $usersForGivenRoute = $commonUserRepo->getUsersForGivenRoute($route);
            foreach ($usersForGivenRoute as $user){
               // var_dump('Check 2');

                $userId = $user->getId();
               // var_dump($userId);
                $dumpPointId = $user->getCollectPointHashId();
                //var_dump($dumpPointId);
                $dumpPointIds = array();
                $dumpPointIds[] = $dumpPointId;
               // var_dump($dumpPointIds);
                $timeToDumpPoint  = Algorithm::getTimeToDumpPoints($routeIntId,$lat,$lng,20,$dumpPointIds,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);

//                var_dump('Check 3');

                $userFromId = $commonUserRepo->profile($userId);
                $notificationsSent = $userFromId->getNotificationSent();
                $userPackage = $userFromId->getCommonUserPackage();

                if($userPackage!=null){
                    $userPackageId = $userPackage->getId();
                }else{
                    $userPackageId= 1;
                }
                
               // var_dump($userPackageId, $notificationsSent);
                //exit();

                if($notificationsSent==false and ($userPackageId!=1 or $userPackageId!=null) and ($timeToDumpPoint[0]<5 and $timeToDumpPoint[0]>0)){
                    $userFromId->setNotificationSent(true);
                    $userMobileNo= array();
                    $tempNo = $user->getContact();
                    $user_name = $user->getName();
                    $userMobileNo[]=$tempNo;

                    $em->flush();

                    $this->getContainer()->get('app.sms_messaging')->sendSMS($userMobileNo,'Hi User, Your assigned Garbage Truck will arrive shortly. Thank you.');

                    //Sending In-App Notification through GCM

                    $userGcm = $user->getGcmCode();

                    if($userGcm !=null){
                        $response = $this->getContainer()->get('app.in_app_notification')->sendInAppNotification($userGcm,'Garbage Truck Alert', 'Hi User, Your assigned Garbage Truck will arrive shortly. Thank you.');

                    }

                }
                //var_dump($userFromId);
                $userFromId->setTimeToReach($timeToDumpPoint[0]);
            }
        }

        $em->flush();

        return null;
    }

}