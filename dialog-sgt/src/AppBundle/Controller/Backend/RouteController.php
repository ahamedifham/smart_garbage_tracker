<?php

namespace AppBundle\Controller\Backend;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Route as RouteEntity;
use AppBundle\Form\Backend\Route as RouteForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RouteController extends BaseController
{
    /**
     * @Route("/route/create", name="app_route_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function createAction(Request $request){
        $object = new RouteEntity();

        $form = $this->generateForm(RouteForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
            $object->setCode('SGT -WP'.$object->getId());//TODO dynamic province code
            $em->flush();

            return $this->redirectToRoute('app_route_list');
        }

        return $this->outputForm($form,'route/create.html.twig',array('page_title'=>'route_info'));
    }

    /**
     * @Route("/route/{id}/edit", name="app_route_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Route');
        $object = $repo->find($id);

        $form = $this->generateForm(RouteForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();
            $object->setCode('SGT -WP'.$object->getId());//TODO dynamic province code
            $em->flush();

            return $this->redirectToRoute('app_route_list');
        }

        return $this->outputForm($form,'route/create.html.twig',array('page_title'=>'route_info'));
    }

    /**
     * @Route("/route/{id}/delete", name="app_route_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Route');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_route_list');
    }

    /**
     * @Route("/route/list", name="app_route_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Route');
        $form = $this->generateForm(RouteForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list,'route/list.html.twig',$form,
            array('page_title'=>'route_info')
        );
    }

    /**
     * @Route("route/findId", name="app_route_find_id")
     * @param Request $request
     * @return Response
     */
    public function findRouteIDAction(Request $request){
        $id = $request->get("id");
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Route');
        $object = $repo->find($id);

        return new Response(json_encode($object->getRouteId()));
    }


    /**
     * @Route("route/id/update" , name="app_route_update_id")
     * @param Request $request
     * @return Response
     */
    public function updateRouteIdAction(Request $request){
        $routeId = $request->get("routeId");
        $startPointLat= $request->get("startPointLat");
        $startPointLng = $request->get("startPointLng");
        $endPointLat = $request->get("endPointLat");
        $endPointLng = $request->get("endPointLng");

        $id = $request->get("id");
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Route');
        $object = $repo->find($id);
        $object->setRouteId($routeId);
        $object->setStartPointLat($startPointLat);
        $object->setStartPointLng($startPointLng);
        $object->setEndPointLat($endPointLat);
        $object->setEndPointLng($endPointLng);
        
        $em->flush();

        return new Response(json_encode($object->getRouteId()));
    }

    /**
     * @Route("route/count", name="app_route_count")
     * @param Request $request
     * @return Response
     */
    public function getCountAction(Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppBundle:Route');
        $count= $repo->count();
        return new Response(json_encode($count));
    }
    

}