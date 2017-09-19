<?php

namespace AppBundle\Command;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\geolocation\LatLng;
use AppBundle\AlertAlgorithm\Algorithm;
use AppBundle\Entity\CommonUser;
use AppBundle\AlertAlgorithm\UserNodeAssign;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Validator\Constraints\DateTime;


class GenerateStructCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName       ('app:generate-struct')
            ->setDescription('assign the given users to respective nodes of the given route(main nodes)')
//            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $argument = $input->getArgument('argument');
//
//        if ($input->getOption('option')) {
//            // ...
//        }
//
//        $output->writeln('Command result.');

        $serializer = new Serializer(
            array(new GetSetMethodNormalizer(), new ArrayDenormalizer()),
            array(new JsonEncoder())
        );

        $alertAlgo      = $this ->getContainer()    ->get('app.alert_algo');
        $scheduler      = $this ->getContainer()    ->get('app.check_schedule');
        $redis          = $this ->getContainer()    ->get('snc_redis.default');
        $em             = $this ->getContainer()    ->get('doctrine.orm.entity_manager');
        $routeRepo      = $em   ->getRepository     ('AppBundle:Route');
        $commonUserRepo = $em   ->getRepository     ('AppBundle:CommonUser');

//        //test point for iLocator API call
//        $trackingUnitRepo = $em->getRepository('AppBundle:TrackingUnit');
//
//        $trackingUnits = $trackingUnitRepo->findBy(array('status'=>1));
//
//        foreach ($trackingUnits as $item) {
//            $dateTime =\DateTime::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'));
//            //filter in tracking units inSchedule
//            if ($scheduler->isInSchedule($item->getId(),$dateTime)) {
//                $result = $this->getContainer()->get('app.mife')->getILocatorManager()->getCurrentLocation($item->getMsisdn());
//
//                $lat = 6.9273640932202; //$result[0]->lat
//                $lng = 79.996716231108; //$result[0]->lon
//                $speed = $alertAlgo->getPredictedSpeed($item->getId(), $lat, $lng, $result[0]->timestamp, $serializer);
//                var_dump('speed= ' . $speed);
//                $nearByUsers = $alertAlgo->getNearbyUsers($lat, $lng, 1, 20, 30, $serializer);
//                var_dump('users=');
//                foreach ($nearByUsers as $user) {
//                    var_dump($user->getName());
//                }
//            }else{
//                //if not in a schedule reset speed prediction
//                $redis->del('trackUnit'.$item->getId().':routeId');
//            }
//
//        }
//        var_dump('end');
//        exit;

        //test point for  getNearbyUsers

//            $lat= 6.9273640932202;
//            $lng = 79.996716231108;
//            $speed=Algorithm::getPredictedSpeed(11,$lat,$lng,20,$redis,$em,$serializer);
//            $out=UserNodeAssign::getNearbyUsers ($lat,$lng,$i,20,30,$redis,$em,$serializer);
//            exit;

        //end test point

        //end test point for iLocator API call

        //test point for isInSchedule function

//        $scheduler=$this->getContainer()->get('app.check_schedule');
//        $dateTime =\DateTime::createFromFormat('Y-m-d H:i:s','2017-07-13 06:53:25');
//
//        var_dump($scheduler->isInSchedule(10,$dateTime));exit;

        //end test point
        //test point for UpdateNodeStruct

//        $newUser= $commonUserRepo->profile(94);
//        $route = $routeRepo->profile(1);
//        $newUser->setRoute($route);
//
//        $testOutput= $alertAlgo->UpdateNodeStruct($newUser,$serializer);
//        var_dump($testOutput);exit;
        //end test point

//        while (true)
//        {
        $routes = $routeRepo->findBy(array('status'=>1));

        foreach($routes as $i_tmp=>$route) {
            if($route->getRouteId() == '0') continue;

            $i          = $route    ->getId();
            $routeId    = $route    ->getRouteId();
            $pathPoints = $this     ->getContainer()->get('app.mongodb_dump_points')->path($routeId);
            $routeIntId = $route    ->getId();
            $mainNodes  = array();

            for ($j = 0; $j < sizeof($pathPoints); $j++) {
                $tempLat        = $pathPoints[$j][0];
                $tempLng        = $pathPoints[$j][1];
                $tempLatLng     = new LatLng(floatval($tempLat), floatval($tempLng));
                $mainNodes[]    = $tempLatLng;
            }

            $returnedValues = $alertAlgo->loadRoute($mainNodes, $routeIntId);

            $nodes              = $returnedValues[0];
            $angleMin           = $returnedValues[1];
            $distanceMin        = $returnedValues[2];
            $hashAngleArray     = $returnedValues[3];
            $hashDistanceArray  = $returnedValues[4];
            $angleDivisor       = $returnedValues[5];
            $distanceDivisor    = $returnedValues[6];
            $accumulateEdge     = $returnedValues[7];

            $jsonMainNodes          = $serializer->serialize($mainNodes,            'json');
            $jsonNodes              = $serializer->serialize($nodes,                'json');
            $jsonAngleMin           = $serializer->serialize($angleMin,             'json');
            $jsonDistanceMin        = $serializer->serialize($distanceMin,          'json');
            $jsonHashAngleArray     = $serializer->serialize($hashAngleArray,       'json');
            $jsonHashDistanceArray  = $serializer->serialize($hashDistanceArray,    'json');
            $jsonAngleDivisor       = $serializer->serialize($angleDivisor,         'json');
            $jsonDistanceDivisor    = $serializer->serialize($distanceDivisor,      'json');
            $jsonAccumulateEdge     = $serializer->serialize($accumulateEdge,       'json');

            //update collecting point of all users in the route
            $usersForGivenRoute = $commonUserRepo->getUsersForGivenRoute($route);

            foreach($usersForGivenRoute as $user){
                $tempIndex =$alertAlgo->snapToNode($routeId,$user->getCollectPointLat(),$user->getCollectPointLng(),$nodes,$angleMin,$distanceMin,$hashAngleArray,$hashDistanceArray,$angleDivisor,$distanceDivisor,$accumulateEdge);
                $user->setCollectPointHashId($tempIndex);
            }
            $em->flush();

            $jsonUsers = $serializer->serialize($usersForGivenRoute, 'json');

            $redis->set('route'.$i.':mainNodes',            $jsonMainNodes);
            $redis->set('route'.$i.':nodes',                $jsonNodes);
            $redis->set('route'.$i.':angleMin',             $jsonAngleMin);
            $redis->set('route'.$i.':distanceMin',          $jsonDistanceMin);
            $redis->set('route'.$i.':hashAngleArray',       $jsonHashAngleArray);
            $redis->set('route'.$i.':hashDistanceArray',    $jsonHashDistanceArray);
            $redis->set('route'.$i.':angleDivisor',         $jsonAngleDivisor);
            $redis->set('route'.$i.':distanceDivisor',      $jsonDistanceDivisor);
            $redis->set('route'.$i.':accumulateEdge',       $jsonAccumulateEdge);
            $redis->set('route'.$i.':users',                $jsonUsers);

            $output->writeln('Route'.$i.' Loading Completed!');

        }
            $output->writeln('Command Completed Successfully!');
//            sleep(1);
//        }

        return null;

    }

}
