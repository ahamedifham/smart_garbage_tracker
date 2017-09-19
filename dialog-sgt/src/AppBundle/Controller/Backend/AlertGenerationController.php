<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/16/16
 * Time: 10:02 AM
 */

namespace AppBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\AlertGeneration;
use AppBundle\Form\Backend\AlertGeneration as AlertGenerationForm;
use Symfony\Component\Routing\Annotation\Route;


class AlertGenerationController extends BaseController
{
    /**
     * @Route("/alert_generation/create", name="app_alert_generation_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request){
        $object = new AlertGeneration();
        $form = $this->generateForm(AlertGenerationForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('AppBundle:AlertGeneration');

            $mobileNumbers = $repo->sendMessages($object->getMessage(), $object->getType());

            $em->persist($object);
            $em->flush();

            //$this->get('app.sms_messaging')->sendSMS(array('+94765243784'),$object->getMessage());

//            var_dump(array('+94765243784') == $mobileNumbers);
//            exit();

           $this->get('app.sms_messaging')->sendSMS($mobileNumbers,$object->getMessage());
            return $this->redirectToRoute('app_alert_generation_list');
        }

        return $this->outputForm($form,'alert_generation/create.html.twig',array('page_title'=>'alert_generation_info'));
    }

    /**
     * @Route("/alert_generation/{id}/edit",name="app_alert_generation_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:AlertGeneration');
        $object=$repo->find($id);

        $form = $this->generateForm(AlertGenerationForm::class,$object,'edit');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_alert_generation_list');
        }
        return $this->outputForm( $form,'alert_generation/create.html.twig',array('page_title'=>'alert_generation_info'));
    }

    /**
     * @Route("/alert_generation/{id}/delete", name="app_alert_generation_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:AlertGeneration');
        $alertGenerationObject = $repo->find($id);
        $alertGenerationObject ->setStatus(false);
        $em->flush($alertGenerationObject );
        return $this->redirectToRoute('app_alert_generation_list');
    }


    /**
     * @Route("/alert_generation/list", name="app_alert_generation_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:AlertGeneration');
        $form = $this->generateForm(AlertGenerationForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $list=$qb->getQuery()->getResult();

        return $this->outputList($list,'alert_generation/list.html.twig',$form,
            array('page_title'=>'alert_generation_info')
        );

    }
}