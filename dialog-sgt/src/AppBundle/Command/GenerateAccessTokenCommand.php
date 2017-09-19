<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 12/6/16
 * Time: 11:47 AM
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\AlertAlgorithm\Algorithm;

class GenerateAccessTokenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        // ...
        $this
            // the name of the command (the part after "app/console")
            ->setName('app:get-access_token')

            // the short description shown while running "php app/console list"
            ->setDescription('Get Access Token')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to get token...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');


        $ideabizAccessCodeRepo = $em->getRepository('AppBundle:IdeabizAccessCode');

        $ideabizAccessCode = $ideabizAccessCodeRepo->profile('MAP_CODE');
        $refreshToken = $ideabizAccessCodeRepo->profile('Refresh_token')->getValue();
        $authorizationCode = $ideabizAccessCodeRepo->profile('Authorization_code')->getValue();
        $accessCode = $ideabizAccessCode->getValue();



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://ideabiz.lk/apicall/token?grant_type=refresh_token&refresh_token=".$refreshToken."&scope=PRODUCTION");
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'Authorization: Basic '.$authorizationCode,
            'Content-Type: application/x-www-form-urlencoded',
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $data = json_decode($server_output);
       // var_dump($data->refresh_token);

        $newAccessCode = $ideabizAccessCodeRepo->profile('MAP_CODE');
        $newRefreshToken = $ideabizAccessCodeRepo->profile('Refresh_token');

        $newAccessCode->setValue($data->access_token);
        $newRefreshToken->setValue($data->refresh_token);

        //print  $server_output ;

            // $em->flush();


        $em->flush();

        return null;
    }
}