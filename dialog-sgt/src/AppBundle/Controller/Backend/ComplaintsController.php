<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/9/16
 * Time: 4:35 AM
 */

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Complaints;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Complaints as ComplaintsEntity;
use AppBundle\Form\Backend\Complaints as ComplaintsForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ComplaintsController extends BaseController
{
    /**
     * //@Route("/complaints/create", name="app_complaints_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function createAction(Request $request){
        $object = new ComplaintsEntity();

        $form = $this->generateForm(ComplaintsForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_complaints_list');
        }

        return $this->outputForm($form,'complaints/create.html.twig',array('page_title'=>'complaints_info'));
    }

    /**
     * @Route("/complaints/{id}/edit", name="app_complaints_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Complaints');
        $object = $repo->find($id);

        $form = $this->generateForm(ComplaintsForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_complaints_list');
        }

        return $this->outputForm($form,'complaints/create.html.twig',array('page_title'=>'complaints_info'));
    }

    /**
     * @Route("/complaints/{id}/delete", name="app_complaints_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Complaints');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_complaints_list');
    }

    /**
     * @Route("/complaints/list", name="app_complaints_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Complaints');
        $form = $this->generateForm(ComplaintsForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list,'complaints/list.html.twig',$form,
            array('page_title'=>'complaints_info')
        );
    }
}