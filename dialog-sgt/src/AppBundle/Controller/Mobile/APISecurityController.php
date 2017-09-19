<?php
/**
 * Created by PhpStorm.
 * User: Isuru
 * Date: 10/12/2016
 * Time: 4:43 PM
 */

namespace AppBundle\Controller\Mobile;

use AppBundle\AlertAlgorithm\Algorithm;
use AppBundle\Entity\CommonUser;
use AppBundle\geolocation\LatLng;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\geolocation;
use AppBundle\geolocation\SphericalGeometry;

class APISecurityController extends APIBaseController
{
    /**
     *
     * @Route("/user/register", name="app_api_user_register")
     */
    public function registerAction(Request $request){
        $data = $this->handleRequest();
        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');

        $password = $this->get('security.password_encoder')
            ->encodePassword(new CommonUser(), $data->password );
        $data->password = $password;

        $checkForUserName = $commonUserRepo->checkForUserName($data);
        $checkForContact = $commonUserRepo->checkForContact($data);
        $checkForEmail = $commonUserRepo->checkForEmail($data);

        if(sizeof($checkForUserName)!=0){
            $responseObj = (object)array(
                'status'=>false,
                'errors'=>'The Username entered already exists. Please Try Another Username',
            );
        }elseif (sizeof($checkForContact) !=0){
            $responseObj = (object)array(
                'status'=>false,
                'errors'=>'The Mobile Number entered already exists. Please Try Another Mobile Number.',
            );
        }elseif (sizeof($checkForEmail)!=0){
            $responseObj = (object)array(
                'status'=>false,
                'errors'=>'The Email entered already exists. Please Try Another Email',
            );
        }else{
            $errors = $commonUserRepo->register($data);

            $SMSCode = rand(1000,9999);
            $name= $data->name;
            $user = $commonUserRepo->getUserFromEmail($data->email);
            $userId = $user->getId();
            $userIdEncrypted = sha1($userId);
            $user->setEmailConfirmCode($userIdEncrypted);
            $user->setSMSCode($SMSCode);
            $em->flush();

            $mobileNumber = array();

            $mobileNumber[] = $data->phone;

            //$this->get('app.sms_messaging')->sendSMS($mobileNumber,$SMSCode);

            $smsManager = $this->get('app.mife')->getSMSManager();

            $smsManager->sendSMS($mobileNumber,$SMSCode);


            $link1 ='http://localhost/dialog-sgt/web/app_dev.php/user/email_confirm/';
            $linkToSend = $link1.$userIdEncrypted;
            $userEmail = $data->email;

            $message = \Swift_Message::newInstance()
                ->setSubject('Welcome to Smart Garbage Tracker')
                ->setFrom('smartgarbagetracker@gmail.com')
                ->setTo($userEmail)
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'backend/Email/registration.html.twig',
                        array('name' => $name , 'link'=> $linkToSend)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);


            $responseObj = (object)array(
                'status'=>!count($errors) ? true:false,
                'errors'=>$errors,
                'SMSCode'=>$SMSCode
            );
        }

        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/test_algorithm", name="app_api_user_test_algorithm")
     */
    public function testAlgorithm(Request $request){
        $mainNodes = array();
        $em = $this->getDoctrine()->getManager();
        $routeRepo= $em->getRepository('AppBundle:Route');
        $count = $routeRepo->count();
        $route = $routeRepo->profile(1);
        $routeId = $route->getRouteId();

        $pathPoints = $this->get('app.mongodb_dump_points')->path($routeId);

        for($i=0;$i<sizeof($pathPoints);$i++){
            $tempLat = $pathPoints[$i][0];
            $tempLng = $pathPoints[$i][1];
            $tempLatLng = new LatLng(floatval($tempLat), floatval($tempLng));
            $mainNodes[] = $tempLatLng;
        }

        Algorithm::LoadRoute($mainNodes,0);

        $testLat =  6.927478;
        $testLng = 79.99762;

        $testOutput= Algorithm::SearchLatLng(0, $testLat, $tempLng);

        $dumpPoints = $this->get('app.mongodb_dump_points')->dumpPoints($routeId);

        $dumPointIds = array();

        for($j=0; $j<sizeof($dumpPoints);$j++){
            $tempDumpLat = $dumpPoints[$j][0];
            $tempDumpLng = $dumpPoints[$j][1];

            $tempIndex = Algorithm::SearchLatLng(0,floatval($tempDumpLat),floatval($tempDumpLng));
            $dumPointIds[] = $tempIndex;
        }

        var_dump($dumPointIds);

        $timeToPoint = Algorithm::getTimeToDumpPoints(0,floatval($dumpPoints[4][0]),floatval($dumpPoints[4][1]),10,$dumPointIds);

        var_dump($timeToPoint);

        return $this->handleResponse(0);
    }

    /**
     *
     * @Route("/user/forgot_password_send_mail", name="app_api_user_forget_password_send_mail")
     */
    public function sendEmailAction(Request $request){
        $data = $this->handleRequest();
        $forgotPasswordCode = rand(1000,9999);

        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');

        $user = $commonUserRepo->getUserForEmail($data->email);

        if(sizeof($user)==0){
            $responseObj = (object)array(
                'status'=>false,
                'errors'=>'Sorry, there is no user from this email. Thank you.',
            );
        }else{
            $responseObj = (object)array(
                'status'=>true,
                'errors'=>null,
                'code'=>$forgotPasswordCode
            );

            $message = \Swift_Message::newInstance()
                ->setSubject('Forget Password Code')
                ->setFrom('smartgarbagetracker@gmail.com')
                ->setTo($data->email)
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        'backend/Email/forgetPassword.html.twig',
                        array('code' => $forgotPasswordCode)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
        }

        return $this->handleResponse($responseObj);
    }

    /**
     *
     * @Route("/user/set_forget_password", name="app_api_user_set_forget_password")
     */
    public function setPasswordAction(Request $request){
        $data = $this->handleRequest();

        $em = $this->getDoctrine()->getManager();
        $commonUserRepo = $em->getRepository('AppBundle:CommonUser');

        $user = $commonUserRepo->getUserForEmail($data->email);
        //var_dump($user[0]);

        $password = $this->get('security.password_encoder')
            ->encodePassword(new CommonUser(), $data->newPassword );
        $data->newPassword = $password;

        $user[0]->setPassword($data->newPassword);

        $errors = $em->flush();
        $responseObj = (object)array(
            'status'=>count($errors)? false:true,
            'errors'=>$errors,
        );

        return $this->handleResponse($responseObj);
    }

}