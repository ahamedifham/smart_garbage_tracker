<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Process\Process;
class EnqueueUsersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName       ('app:enqueue-users')
            ->setDescription('generate a list of users depending on the arrival time of truck to be sent alerts to')
            ->addArgument   ('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption     ('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alertAlgo  = $this->getContainer()->get('app.alert_algo');
        $scheduler  = $this->getContainer()->get('app.check_schedule');
        $redis      = $this->getContainer()->get('snc_redis.default');
        $em         = $this->getContainer()->get('doctrine.orm.entity_manager');

        $trackingUnitRepo   = $em->getRepository('AppBundle:TrackingUnit');

        $trackingUnits      = $trackingUnitRepo->findBy(array('status'=>1));

        //generate list of users to be sent alerts with
        $enqueuedUsers  = array();

        foreach ($trackingUnits as $item) { //TODO : use multi curl with batch processing
            $dateTime =\DateTime::createFromFormat('Y-m-d H:i:s',date('Y-m-d H:i:s'));
            var_dump($scheduler->isInSchedule($item->getMsisdn(),$dateTime));
            //filter in tracking units inSchedule
            if ($scheduler->isInSchedule($item->getMsisdn(),$dateTime)) {
            //if (true) {

                $result = $this->getContainer()->get('app.mife')->getILocatorManager()->getCurrentLocation($item->getMsisdn());

//                $lat = 6.9273640932202; //$result[0]->lat
//                $lng = 79.996716231108; //$result[0]->lon

                $lat        =$result[0] ->lat;
                $lng        =$result[0] ->lon;

                var_dump($lat, $lng);

               // $routeId    =$item      ->getRoute()->getId();

                //Get the Route Id from Scheduler

                $checkForRouteId = $scheduler->getRouteIdFromSchedule($item->getMsisdn());

                if($checkForRouteId != false){
                    $routeId = $checkForRouteId;
                }else{
                    var_dump('There is no schedule today');
                    $routeId=1; //This has to be resolved
                }

                var_dump('Route Id is');
                var_dump($routeId);

                //$routeId    =4;

                var_dump('timestamp is');
                var_dump($result[0]->timestamp);

                $speed      = $alertAlgo->getPredictedSpeed($item->getId(), $lat, $lng, $result[0]->timestamp);
               // $speed      = 30*3.6;

                var_dump('speed is');
                var_dump($speed);

                $timeRange  = $this     ->getContainer()    ->getParameter('app.alert_max_arrival_time');


                var_dump('time range is');
                var_dump($timeRange);

                $nearByUsers= $alertAlgo->getNearbyUsers($lat, $lng, $routeId, $speed, $timeRange);
               // $nearByUsers= $alertAlgo->getNearbyUsers($lat, $lng, $routeId, $speed, 50);
                var_dump('Near by users in enque');
                var_dump(sizeof($nearByUsers));

                foreach ($nearByUsers as $user) {
                    $userId             = $user  ->id;
                    $alertAlreadySent   = $redis ->get('alertedUser'.$userId);        //check if user is already alerted

                    var_dump('Alert ALready sent value');
                    var_dump($alertAlreadySent);

                    if (is_null($alertAlreadySent)){
                        $enqueuedUsers[]    = $user;
                        $redis  ->setnx   ('alertedUser'.$userId,1);      //flag user as alerted
                        $redis  ->expire('alertedUser'.$userId,43200);      //expire flag in 24hrs TODO think about this
                    }
                }

            }else {
                var_dump('No Schedule Found');
                $redis->set('trackUnit'.$item->getId().':isInSchedule',false);    //if not in a schedule reset speed prediction

            }

        }

        $userBatch      = array();
        $userBatchSize  = $this->getContainer()->getParameter('app.alert_batch_size');
        $userBatchCount = 0;
        $totalUserCount = count($enqueuedUsers);
        $microtime      = microtime(false);



        foreach ($enqueuedUsers as $i=>$enqueuedUser) {
            $userBatch[] = $enqueuedUser;
            if(count($userBatch) == $userBatchSize ||
                ( $totalUserCount-$userBatchSize*($userBatchCount+1) < $userBatchCount))
            {
                $redisBatchKey = bin2hex(openssl_random_pseudo_bytes(10)).'_'.$userBatchCount;
                var_dump($redisBatchKey);
                $userBatchCount++;

                $redis  ->set($redisBatchKey,json_encode($userBatch));
//                shell_exec('nohup php app/console app:send-alerts '.$redisBatchKey. " > /dev/null &");
                shell_exec('php app/console app:send-alerts '.$redisBatchKey);
                $userBatch = array();
            }
            $output->writeln('Alert sent to '.$enqueuedUser->name.', Tele no: '.$enqueuedUser->contact);
            
            
        }
        $output->writeln('Alert sending complete!');
    }

}
