<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/15/16
 * Time: 1:37 PM
 */

namespace AppBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CommonUserPackage;
use AppBundle\Form\Backend\CommonUserPackage as CommonUserPackageForm;
use Symfony\Component\Routing\Annotation\Route;

class CommonUserPackageController extends BaseController
{
    /**
     * @Route("/common_user_package/create", name="app_common_user_package_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request){
        $object = new CommonUserPackage();
        $form = $this->generateForm(CommonUserPackageForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_common_user_package_list');
        }

        return $this->outputForm($form,'common_user_package/create.html.twig',array('page_title'=>'common_user_package_info'));
    }
    /**
     * @Route("/common_user_package/{id}/edit",name="app_common_user_package_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:CommonUserPackage');
        $object=$repo->find($id);

        $form = $this->generateForm(CommonUserPackageForm::class,$object,'edit');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_common_user_package_list');
        }
        return $this->outputForm( $form,'common_user_package/create.html.twig',array('page_title'=>'common_user_package_info'));
    }
    /**
     * @Route("/common_user_package/{id}/delete", name="app_common_user_package_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:CommonUserPackage');
        $commonUserObject = $repo->find($id);
        $commonUserObject ->setStatus(false);
        $em->flush($commonUserObject );
        return $this->redirectToRoute('app_common_user_package_list');
    }
    //Don't need delete for user

    /**
     * @Route("/common_user_package/list", name="app_common_user_package_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:CommonUserPackage');
        $form = $this->generateForm(CommonUserPackageForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $list=$qb->getQuery()->getResult();

        return $this->outputList($list,'common_user_package/list.html.twig',$form,
            array('page_title'=>'common_user_package_info')
        );

    }
}