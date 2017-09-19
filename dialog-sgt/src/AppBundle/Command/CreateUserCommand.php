<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\AlertAlgorithm\Algorithm;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        // ...
        $this
            // the name of the command (the part after "app/console")
            ->setName('app:get-truck')

            // the short description shown while running "php app/console list"
            ->setDescription('Get Truck Locations')

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
        $ideabizAccessCodeRepo = $em->getRepository('AppBundle:IdeabizAccessCode');

        $ideabizAccessCode = $ideabizAccessCodeRepo->profile('MAP_CODE');
        $accessCode = $ideabizAccessCode->getValue();

        $trackingUnits = $trackingUnitRepo->findBy(array('status'=>1));
        
        foreach ($trackingUnits as $item) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://ideabiz.lk/apicall/iLocate/v1/getCurrentLocation/715324145?number=".$item->getMsisdn());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $headers = [
                'Authorization: Bearer '.$accessCode,
                'Content-Type: application/x-www-form-urlencoded',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $server_output = curl_exec ($ch);
            curl_close ($ch);
            $data = json_decode($server_output);

            $trackingUnit = $trackingUnitRepo->getUnit(intval($data[0]->number));
            $trackingUnitLocate = $trackingUnitLocateRepo->getUnitLocate($trackingUnit);

            $trackingUnitLocate->setName($data[0]->name);
            $trackingUnitLocate->setLat(floatval($data[0]->lat));
            $trackingUnitLocate->setLng(floatval($data[0]->lon));

           // $em->flush();
        }

        $em->flush();

        return null;
    }
}