<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 8/23/16
 * Time: 9:51 AM
 */

namespace AppBundle\Controller\Backend;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\EndPoint;
use AppBundle\Form\Backend\EndPoint as EndPointForm;
use Symfony\Component\Routing\Annotation\Route;


class EndPointController extends BaseController
{
    /**
     * @Route("/end_point/create", name="app_end_point_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function createAction(Request $request){
        $object = new EndPoint();
        $form = $this->generateForm(EndPointForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_end_point_list');
        }

        return $this->outputForm($form,'end_point/create.html.twig',array('page_title'=>'end_point_info'));
    }

    /**
     * @Route("/end_point/{id}/edit", name="app_end_point_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:EndPoint');
        $object = $repo->find($id);

        $form = $this->generateForm(EndPointForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_end_point_list');
        }

        return $this->outputForm($form,'end_point/create.html.twig',array('page_title'=>'end_point_info'));
    }
    /**
     * @Route("/end_point/{id}/delete", name="app_end_point_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:EndPoint');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_end_point_list');
    }

    /**
     * @Route("/end_point/list", name="app_end_point_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:EndPoint');
        $form = $this->generateForm(EndPointForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list,'end_point/list.html.twig',$form,
            array('page_title'=>'end_point_info')
        );
    }

    public function updateAction(Request $request){
        $id = $request->get("id");
        $lat= $request->get("lat");
        $lng = $request->get("lng");
        $name= $request->get("name");
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:EndPoint');
        $object = $repo->find($id);

        if(!$object){
            $this->createEndPoint($lat, $lng, $name);
        }else{
            $object->setLat($lat);
            $object->setLng($lng);
        }
    }


}