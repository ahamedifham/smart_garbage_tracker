<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/2/16
 * Time: 1:33 PM
 */

namespace AppBundle\Controller\Backend;
use AppBundle\Entity\SmartBin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\SmartBin as SmartBinEntity;
use AppBundle\Form\Backend\SmartBin as SmartBinForm;

class SmartBinController extends BaseController
{
    /**
     * @Route("/smart_bin/create", name="app_smart_bin_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function createAction(Request $request){
        $object = new SmartBinEntity();

        $form = $this->generateForm(SmartBinForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_smart_bin_list');
        }

        return $this->outputForm($form,'smart_bin/create.html.twig',array('page_title'=>'smart_bin_info'));
    }

    /**
     * @Route("/smart_bin/{id}/edit", name="app_smart_bin_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:SmartBin');
        $object = $repo->find($id);

        $form = $this->generateForm(SmartBinForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_smart_bin_list');
        }

        return $this->outputForm($form,'smart_bin/create.html.twig',array('page_title'=>'smart_bin_info'));
    }

    /**
     * @Route("/smart_bin/{id}/delete", name="app_smart_bin_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:SmartBin');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_smart_bin_list');
    }

    /**
     * @Route("/smart_bin/list", name="app_smart_bin_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:SmartBin');
        $form = $this->generateForm(SmartBinForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list,'smart_bin/list.html.twig',$form,
            array('page_title'=>'smart_bin_info')
        );
    }

    /**
     * @Route("/smart_bin/find_route_id", name="app_smart_bin_find_route_id")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function findRouteIdAction(Request $request){
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:SmartBin');
        $smartBin = $repo->getSmartBinFromId($id);
        $routeId = $smartBin->getRoute()->getRouteId();

        return new Response(json_encode($routeId));

    }
}