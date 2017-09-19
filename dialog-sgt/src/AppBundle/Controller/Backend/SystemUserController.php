<?php

namespace AppBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\SystemUser;
use AppBundle\Form\Backend\SystemUser as SystemUserForm;
use Symfony\Component\Routing\Annotation\Route;

class SystemUserController extends BaseController
{
    /**
     * @Route("/system_user/create", name="app_system_user_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function createAction(Request $request)
    {
        $object = new SystemUser();

        $form = $this->generateForm(SystemUserForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $object = $form->getData();
            $password = $this->get('security.password_encoder')
                ->encodePassword($object, $object->getPassword());
            $object->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_system_user_list');
        }
        return $this->outputForm($form,'system_user/create.html.twig',array('page_title'=>'system_user_info'));

    }

    /**
     * @Route("/system_user/{id}/edit",name="app_system_user_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:SystemUser');
        $object=$repo->find($id);

        $form = $this->generateForm(SystemUserForm::class,$object,'edit');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $password = $this->get('security.password_encoder')
                ->encodePassword($object, $object->getPassword());
            $object->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();


            return $this->redirectToRoute('app_system_user_list');

        }

        return $this->outputForm( $form,'system_user/create.html.twig',array('page_title'=>'system_user_info'));

    }

    /**
     * @Route("/system_user/{id}/delete", name="app_system_user_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:SystemUser');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_system_user_list');

    }


    /**
     * @Route("/system_user/list", name="app_system_user_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:SystemUser');
        $form = $this->generateForm(SystemUserForm::class, null, 'search');

        $qb = $repo->backendListSearch($form, $request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list, 'system_user/list.html.twig', $form,
            array('page_title' => 'system_user_info')
        );
    }
}
