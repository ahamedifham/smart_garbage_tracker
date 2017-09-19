<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/2/16
 * Time: 3:39 PM
 */

namespace AppBundle\Controller\Mobile;
use AppBundle\RepositoryORM\Base;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SmartBinAPIController extends APIBaseController
{
    /**
     * @Route("/smart_bin/get_data", name="app_smart_bin_")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getDataAction(Request $request){
        return new Response(
            'Smart Bin id: 1234'
        );
    }

    /**
     * @Route("/smart-bin/get-bin-level", name="app_smart_bin_get_bin_level")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getBinLevel(Request $request){
        $data = $this->handleRequest();

        $event = $data->event_name;
        $serial = $data->serial;
        $value = intval($data->value);

        $em = $this->getDoctrine()->getEntityManager();
        $smartBinRepo = $em->getRepository('AppBundle:SmartBin');
        $smartBinBinLevelRepo = $em->getRepository('AppBundle:SmartBinBinLevelEvent');

        $smartBin = $smartBinRepo->profile($serial);
        $smartBinBinLevel = $smartBinBinLevelRepo->profile($event);

        $smartBin->setBinLevel($value);
        $smartBin->setSmartBinBinLevelEvent($smartBinBinLevel);


        $binSerial = $smartBin->getSerial();
        $binAddress = $smartBin->getUserAddress();
        $binUser = $smartBin->getUser();
        $userEmail = $smartBin->getUserEmail();

        $userMobileNo1 = $smartBin->getUserMobileOne();
        $userMobileNo2 = $smartBin->getUserMobileTwo();
        $userMobileNo3 = $smartBin->getUserMobileThree();

        $userMobileNumbers = array();
        if ($userMobileNo1 !=null){
            $userMobileNumbers[] = $userMobileNo1;
        }
        if ($userMobileNo2 !=null){
            $userMobileNumbers[] = $userMobileNo2;
        }
        if ($userMobileNo3 !=null){
            $userMobileNumbers[] = $userMobileNo3;
        }

        //OLD Service to send SMS.

//        $this->get('app.sms_messaging')->sendSMS($userMobileNumbers,$this->renderView(
//        // app/Resources/views/Emails/registration.html.twig
//            'backend/Email/binSMS.html.twig',
//            array('name' => $binUser , 'event'=> $event , 'value' => $value , 'address' =>$binAddress , 'serial'=>$binSerial)
//        ),
//            'text/html');

        $smsManager     = $this->get('app.mife')->getSMSManager();

        $returnSMS = $smsManager->sendSMS($userMobileNumbers,$this->renderView(
        // app/Resources/views/Emails/registration.html.twig
            'backend/Email/binSMS.html.twig',
            array('name' => $binUser , 'event'=> $event , 'value' => $value , 'address' =>$binAddress , 'serial'=>$binSerial)
        ),
            'text/html');


        var_dump($returnSMS);


        $em->flush();
        var_dump($userEmail);
        $message = \Swift_Message::newInstance()
            ->setSubject('Alert : Check your Smart Bin')
            ->setFrom('smartgarbagetracker@gmail.com')
            ->setTo($userEmail)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'backend/Email/binLevel.html.twig',
                    array('name' => $binUser , 'event'=> $event , 'value' => $value)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);


        $responseObj = (object)array(
            'status'=>count(1)? false:true,
            'errors'=>1,
        );

        return $this->handleResponse($responseObj);

    }

    /**
     * @Route("/smart-bin/get-bin-bat-level", name="app_smart_bin_get_bin_bat_level")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getBinBatteryLevelAction(Request $request){
        $data = $this->handleRequest();

        $event = $data->event_name;
        $serial = $data->serial;
        $value = intval($data->value);

        $em = $this->getDoctrine()->getEntityManager();
        $smartBinRepo = $em->getRepository('AppBundle:SmartBin');
        $smartBinBatLevelRepo = $em->getRepository('AppBundle:SmartBinBatLevelEvent');

        $smartBin = $smartBinRepo->profile($serial);
        $smartBinBatLevel = $smartBinBatLevelRepo->profile($event);

        $smartBin->setBatteryLevel($value);
        $smartBin->setSmartBinBatLevelEvent($smartBinBatLevel);

        $binUser = $smartBin->getUser();
        $binSerial = $smartBin->getSerial();
        $binAddress = $smartBin->getUserAddress();
        $binUser = $smartBin->getUser();
        $userEmail = $smartBin->getUserEmail();

        $userMobileNo1 = $smartBin->getUserMobileOne();
        $userMobileNo2 = $smartBin->getUserMobileTwo();
        $userMobileNo3 = $smartBin->getUserMobileThree();

        $userMobileNumbers = array();
        if ($userMobileNo1 !=null){
            $userMobileNumbers[] = $userMobileNo1;
        }
        if ($userMobileNo2 !=null){
            $userMobileNumbers[] = $userMobileNo2;
        }
        if ($userMobileNo3 !=null){
            $userMobileNumbers[] = $userMobileNo3;
        }

        $em->flush();

//        $this->get('app.sms_messaging')->sendSMS($userMobileNumbers,$this->renderView(
//        // app/Resources/views/Emails/registration.html.twig
//            'backend/Email/binSMS.html.twig',
//            array('name' => $binUser , 'event'=> $event , 'value' => $value)
//        ),
//            'text/html');

        $smsManager     = $this->get('app.mife')->getSMSManager();

        $returnSMS = $smsManager->sendSMS($userMobileNumbers,$this->renderView(
        // app/Resources/views/Emails/registration.html.twig
            'backend/Email/binSMS.html.twig',
            array('name' => $binUser , 'event'=> $event , 'value' => $value , 'address' =>$binAddress , 'serial'=>$binSerial)
        ),
            'text/html');

        $message = \Swift_Message::newInstance()
            ->setSubject('Alert : Check your Smart Bin')
            ->setFrom('smartgarbagetracker@gmail.com')
            ->setTo($userEmail)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'backend/Email/binLevel.html.twig',
                    array('name' => $binUser , 'event'=> $event , 'value' => $value)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);

        $responseObj = (object)array(
            'status'=>count(1)? false:true,
            'errors'=>1,
        );

        return $this->handleResponse($responseObj);
    }


}