<?php

namespace AppBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Driver;
use AppBundle\Form\Backend\Driver as DriverForm;
use Symfony\Component\Routing\Annotation\Route;

class DriverController extends BaseController
{
    /**
     * @Route("/driver/create", name="app_driver_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request){
        $object = new Driver();
        $form = $this->generateForm(DriverForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_driver_list');
        }

        return $this->outputForm($form,'driver/create.html.twig',array('page_title'=>'driver_info'));
    }

    /**
     * @Route("/driver/{id}/edit", name="app_driver_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Driver');
        $object = $repo->find($id);
//        var_dump($object);
//        exit();

        $form = $this->generateForm(DriverForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_driver_list');
        }

        return $this->outputForm($form,'driver/create.html.twig',array('page_title'=>'driver_info'));
    }

    /**
     * @Route("/driver/{id}/delete", name="app_driver_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Driver');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_driver_list');
    }

    /**
     * @Route("/driver/list", name="app_driver_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Driver');
        $form = $this->generateForm(DriverForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list,'driver/list.html.twig',$form,
            array('page_title'=>'driver_info')
        );
    }

}
