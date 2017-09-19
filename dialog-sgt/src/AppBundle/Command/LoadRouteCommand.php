<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 12/8/16
 * Time: 10:04 PM
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\AlertAlgorithm\Algorithm;
use Symfony\Component\HttpKernel\EventListener\ValidateRequestListener;
use AppBundle\geolocation\LatLng;



class LoadRouteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        // ...
        $this
            // the name of the command (the part after "app/console")
            ->setName('app:set-route')

            // the short description shown while running "php app/console list"
            ->setDescription('Load Routes')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to Load Routes and Create Hash Indexes for users ")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $routeRepo= $em->getRepository('AppBundle:Route');
        $commonUserRepo= $em->getRepository('AppBundle:CommonUser');
        $count = $routeRepo->count();

        for($i=1; $i<$count+1;$i++){
            $route = $routeRepo->profile($i);
            $routeId = $route->getRouteId();
            var_dump($routeId);
            $pathPoints = $this->getContainer()->get('app.mongodb_dump_points')->path($routeId);
            if($pathPoints=='no_path'){
                var_dump('no_path');
            }
            var_dump('Size of path points is '.sizeof($pathPoints));
            $routeIntId = $route->getId();
            $mainNodes = array();

            for($j=0;$j<sizeof($pathPoints);$j++){
                $tempLat = $pathPoints[$j][0];
                $tempLng = $pathPoints[$j][1];
                $tempLatLng = new LatLng(floatval($tempLat), floatval($tempLng));
                $mainNodes[] = $tempLatLng;
            }

            $returnedValues = Algorithm::LoadRoute($mainNodes,$routeIntId);

            $nodes = $returnedValues[0];
            $angleMin = $returnedValues[1];
            $distanceMin = $returnedValues[2];
            $hashAngleArray = $returnedValues[3];
            $hashDistanceArray = $returnedValues[4];
            $angleDivisor = $returnedValues[5];
            $distanceDivisor = $returnedValues[6];
            $accumulateEdge = $returnedValues[7];

            $this->getContainer()->get('app.mongodb_dump_points')->setNodes($routeId, $nodes);
            $this->getContainer()->get('app.mongodb_dump_points')->setAngleMin($routeId, $angleMin);
            $this->getContainer()->get('app.mongodb_dump_points')->setDistanceMin($routeId, $distanceMin);
            $this->getContainer()->get('app.mongodb_dump_points')->setHashAngleArray($routeId, $hashAngleArray);
            $this->getContainer()->get('app.mongodb_dump_points')->setHashDistanceArray($routeId, $hashDistanceArray);
            $this->getContainer()->get('app.mongodb_dump_points')->setAngleDivisor($routeId, strval($angleDivisor));
            $this->getContainer()->get('app.mongodb_dump_points')->setDistanceDivisor($routeId, strval($distanceDivisor));
            $this->getContainer()->get('app.mongodb_dump_points')->setAccumulateEdge($routeId, $accumulateEdge);

            $usersForGivenRoute = $commonUserRepo->getUsersForGivenRoute($route);
            $latAndLngsForUsersInGivenRoute = array();

            foreach ($usersForGivenRoute as $user){
//                $userTempLat = $user->getCollectPointLat();
//                $userTempLng = $user->getCollectPointLng();
                $userTempLat = $user->getAssignedPointLat();
                $userTempLng = $user->getAssignedPointLng();
                $userId = $user->getId();
                var_dump('User Id is '.$user->getId());
                $userTempLatAndLng = array();
                $userTempLatAndLng[] = $userTempLat;
                $userTempLatAndLng[] = $userTempLng;
                $userTempLatAndLng[] = $userId;

                $latAndLngsForUsersInGivenRoute[] = $userTempLatAndLng;
            }
            var_dump('came 6');
            $collectionPointIds = array();
            foreach ($latAndLngsForUsersInGivenRoute as $temLatAndLng){
                $tempUserLat = $temLatAndLng[0];
                $tempUserLng = $temLatAndLng[1];
                $tempIndex = Algorithm::SearchLatLng($routeIntId,$tempUserLat,$tempUserLng,$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);
                var_dump('Temp index is '.$tempIndex);
                $userFromId = $commonUserRepo->profile($temLatAndLng[2]);
                $userFromId->setCollectPointHashId($tempIndex);
                $userFromId->setNotificationSent(false);
                $collectionPointIds[] = $tempIndex;
            }

            $em->flush();
         }
        var_dump('came 7');

//Load route
        //generate structure
        //enqueue users
        
        
    }
}