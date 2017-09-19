<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/21/16
 * Time: 4:28 PM
 */

namespace AppBundle\Controller\Backend;
use AppBundle\Entity\Schedule;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Schedule as ScheduleEntity;
use AppBundle\Form\Backend\Schedule as ScheduleForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends BaseController
{
    /**
     * @Route("/schedule/create", name="app_schedule_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function createAction(Request $request){
        $object = new ScheduleEntity();

        $form = $this->generateForm(ScheduleForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_schedule_list');
        }

        return $this->outputForm($form,'schedule/create.html.twig',array('page_title'=>'schedule_info'));
    }

    /**
     * @Route("/schedule/{id}/edit", name="app_schedule_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Schedule');
        $object = $repo->find($id);

        $form = $this->generateForm(ScheduleForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_schedule_list');
        }

        return $this->outputForm($form,'schedule/create.html.twig',array('page_title'=>'schedule_info'));
    }

    /**
     * @Route("/schedule/{id}/delete", name="app_schedule_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Schedule');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_schedule_list');
    }

    /**
     * @Route("/schedule/list", name="app_schedule_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Schedule');
        $form = $this->generateForm(ScheduleForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list,'schedule/list.html.twig',$form,
            array('page_title'=>'schedule_info')
        );
    }


}