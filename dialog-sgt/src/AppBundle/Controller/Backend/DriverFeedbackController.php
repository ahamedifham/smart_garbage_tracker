<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/27/16
 * Time: 11:40 AM
 */

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\DriverFeedback;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\DriverFeedback as DriverFeedbackEntity;
use AppBundle\Form\Backend\DriverFeedback as DriverFeedbackForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DriverFeedbackController extends BaseController
{
    /**
     * //@Route("/driver_feedback/create", name="app_driver_feedback_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function createAction(Request $request){
        $object = new DriverFeedbackEntity();

        $form = $this->generateForm(DriverFeedbackForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_driver_feedback_list');
        }

        return $this->outputForm($form,'driver_feedback/create.html.twig',array('page_title'=>'driver_feedback_info'));
    }

    /**
     * @Route("/driver_feedback/{id}/edit", name="app_driver_feedback_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:DriverFeedback');
        $object = $repo->find($id);

        $form = $this->generateForm(DriverFeedbackForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_driver_feedback_list');
        }

        return $this->outputForm($form,'driver_feedback/create.html.twig',array('page_title'=>'driver_feedback_info'));
    }

    /**
     * @Route("/driver_feedback/{id}/delete", name="app_driver_feedback_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:DriverFeedback');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_driver_feedback_list');
    }

    /**
     * @Route("/driver_feedback/list", name="app_driver_feedback_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:DriverFeedback');
        $form = $this->generateForm(DriverFeedbackForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list,'driver_feedback/list.html.twig',$form,
            array('page_title'=>'driver_feedback_info')
        );
    }
}