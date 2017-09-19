<?php

namespace AppBundle\Controller\Backend;
/**
 * Created by PhpStorm.
 * User: Isuru
 * Date: 8/5/2016
 * Time: 11:33 AM
 */
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    /**
     * Generate form with different form options
     * @param $namespace
     * @param $object
     * @param $mode : create,edit,search
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    protected function generateForm($namespace,$object,$mode,$options = array()){
        switch ($mode){
            case 'create':
                return $this->createForm($namespace,$object,array_merge(array('mode'=>'create','translation_domain' => 'backend','attr'=>array('id'=>'data_entry_form')),$options));
            case 'edit':
                return $this->createForm($namespace,$object,array_merge(array('mode'=>'edit','translation_domain' => 'backend','attr'=>array('id'=>'data_entry_form')),$options));
            case 'search':
                return $this->createForm($namespace,$object,array_merge(array('mode'=>'search','translation_domain' => 'backend','method'=>'GET','attr'=>array('novalidate'=>'novalidate')),$options));

            default:
                return $this->createForm($namespace,$object,$options);
        }
    }

    /**
     * @param $form
     * @param $template
     * @param $pageTexts
     * @return Response
     */
    protected function outputForm($form,$template,$pageTexts){
        $variablePassArr = array(
            'form' => $form->createView(),
        );
        $variablePassArr = array_merge($pageTexts,$variablePassArr);
        return $this->render('backend/'.$template, $variablePassArr);
    }

    /**
     * @param $list
     * @param $template
     * @param array $pageTexts
     * @param null $searchForm
     * @return Response
     */
    protected function outputList($list,$template,$searchForm=null,$pageTexts=array()){
        $variablePassArr = isset($searchForm) ? array(
            'searchForm' => $searchForm->createView(),
            'list'=>$list
        ) : array('list'=>$list);
        $variablePassArr = array_merge($pageTexts,$variablePassArr);
        return $this->render('backend/'.$template, $variablePassArr);
    }

    /**
     * @Route("/test/send_email", name="test_send_email")
     */
    public function sendEmail(Request $request)
    {
        $name='Ahamed';
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('ifham38@gmail.com')
            ->setTo('ifham39@gmail.com')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'backend/Email/registration.html.twig',
                    array('name' => $name)
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $this->get('mailer')->send($message);
        return new Response('ok',200);
        // return $this->render(...);
    }

    /**
     * @Route("/test/send_sms", name="test_send_sms")
     */
    public function sendSMS()
    {
        $response = $this->get('app.sms_messaging')->sendSMS(array('+94717664232'),'Hi Ahamed');
        var_dump($response);
        exit;
    }

    /**
     * @Route("/test/send_in_app_notification", name="test_in_app_notification")
     */
    public function sendInAppNotification()
    {
        $gcm = 'csG9Yrl1xD4:APA91bFQ9q8sfPVVrpkP40G-PkM-oM-iDD28WFD61jf23zENHChx5nBw1oPh6YVNCaOL1A3ni0LJvqM1shkwnyxmISt05Kme2cMbVt6RrEONb6Xw68gwKdYSr2G_kJr-qXbL76F-cDzj';
        $response = $this->get('app.in_app_notification')->sendInAppNotification($gcm,'Title Check', 'Message');
        var_dump($response);
    }
}