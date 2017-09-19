<?php

namespace AppBundle\Controller\Backend;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\TrackingUnit;
use AppBundle\Form\Backend\TrackingUnit as TrackingUnitForm;
use Symfony\Component\Routing\Annotation\Route;
class TrackingUnitController extends BaseController
{
    /**
     * @Route("/tracking_unit/create", name="app_tracking_unit_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request){
        $object = new TrackingUnit();
        $form = $this->generateForm(TrackingUnitForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $embeddedObject = $object->getVehicle();
            $embeddedObject->setOwnerCompany($object->getOwnerCompany());
             $em = $this->getDoctrine()->getManager();
             $em->persist($object);
             $em->flush();

            return $this->redirectToRoute('app_tracking_unit_list');
        }
        return $this->outputForm($form,'tracking_unit/create.html.twig',array('page_title'=>'tracker_info'));
    }

    /**
     * @Route("/tracking_unit/{id}/edit", name="app_tracking_unit_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:TrackingUnit');
        $object = $repo->find($id);

        $form = $this->generateForm(TrackingUnitForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $embeddedObject = $object->getVehicle();
            $embeddedObject->setOwnerCompany($object->getOwnerCompany());
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_tracking_unit_list');
        }

        return $this->outputForm( $form,'tracking_unit/create.html.twig',array('page_title'=>'tracker_info'));
    }

    /**
     * @Route("/tracking_unit/{id}/delete", name="app_tracking_unit_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:TrackingUnit');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_tracking_unit_list');
    }

    /**
     * @Route("/tracking_unit/list", name="app_tracking_unit_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:TrackingUnit');
        $form = $this->generateForm(TrackingUnitForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list=$qb->getQuery()->getResult();

        return $this->outputList($list,'tracking_unit/list.html.twig',$form,
            array('page_title'=>'tracker_info')
        );
    }

    /**
     * @Route("/tracking_unit/map", name="app_tracking_unit_map")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mapAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:TrackingUnit');
        $form = $this->generateForm(TrackingUnitForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list=$qb->getQuery()->getResult();

        return $this->outputList($list,'tracking_unit/map.html.twig',$form,
            array('page_title'=>'tracker_map')
        );
    }

    /**
     * @Route("/tracking_unit/get_route_id" , name="app_tracking_unit_route_id")
     * @param Request $request
     * @return Response
     */
    public function getRouteId(Request $request){
        $id= $request->get("id");
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:TrackingUnit');
        $object= $repo->routeIdGet($id);
        $route= $object->getRoute();
        $routeId = $route->getRouteId();
        return new Response(json_encode($routeId));
    }
}

