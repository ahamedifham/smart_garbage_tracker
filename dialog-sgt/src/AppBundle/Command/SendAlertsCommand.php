<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendAlertsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName       ('app:send-alerts')
            ->setDescription('...')
            ->addArgument   ('db-user-key', InputArgument::OPTIONAL, 'In memory key for the users list')
            ->addOption     ('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dbKey  = $input    ->getArgument('db-user-key');
        $redis  = $this     ->getContainer()    ->get('snc_redis.default');

        if ($input->getOption('option')) {
            // ...
        }

        $users      = json_decode($redis->get($dbKey));
        $addresses  = array();

        foreach ($users as $user)   $addresses[] = $user->contact;

        $smsManager     = $this->getContainer()->get('app.mife')->getSMSManager();

        $smsManager     ->sendSMS($addresses,'Hi User, Your assigned Garbage Truck will arrive shortly. Thank you. (N)'); //TODO DON'T HARDCODE MESSAGES
        $redis          ->del($dbKey);
        $this           ->getContainer()->get('logger')->info('messages sent batch '.$dbKey);
        $this           ->getContainer()->get('logger')->info('checked time '.microtime());
        $output         ->writeln       ('messages sent batch '.$dbKey);



        //TODO add GCM here

        foreach ($users as $tempUser){
            $userGcm = $tempUser->gcmCode;
            if($userGcm !=null){
                $response = $this->getContainer()->get('app.in_app_notification')->sendInAppNotification($userGcm,'Garbage Truck Alert', 'Hi User, Your assigned Garbage Truck will arrive shortly. Thank you. (N)');

            }
        }
    }

}
