<?php

namespace AppBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CommonUser;
use AppBundle\Form\Backend\CommonUser as CommonUserForm;
use Symfony\Component\Routing\Annotation\Route;
class CommonUserController extends BaseController
{

    /**
     * //@Route("/common_user/create", name="app_common_user_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request){
        $object = new CommonUser();
        $form = $this->generateForm(CommonUserForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_common_user_list');
        }

        return $this->outputForm($form,'common_user/create.html.twig',array('page_title'=>'common_user_info'));
    }
     /**
      * @Route("/common_user/{id}/edit",name="app_common_user_edit")
      * @param Request $request
      * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
      */
    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:CommonUser');
        $object=$repo->find($id);

        $form = $this->generateForm(CommonUserForm::class,$object,'edit');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_common_user_list');
        }
        return $this->outputForm( $form,'common_user/create.html.twig',array('page_title'=>'common_user_info'));
    }
    /**
     * @Route("/common_user/{id}/delete", name="app_common_user_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:CommonUser');
        $commonUserObject = $repo->find($id);
        $commonUserObject ->setStatus(false);
        $em->flush($commonUserObject );
        return $this->redirectToRoute('app_common_user_list');
    }
    //Don't need delete for user

    /**
     * @Route("/common_user/list", name="app_common_user_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:CommonUser');
        $form = $this->generateForm(CommonUserForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $list=$qb->getQuery()->getResult();

        return $this->outputList($list,'common_user/list.html.twig',$form,
            array('page_title'=>'common_user_info')
        );

    }
    
}
