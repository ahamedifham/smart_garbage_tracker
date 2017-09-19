<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/8/16
 * Time: 7:48 PM
 */

namespace AppBundle\Controller\Backend;
use AppBundle\Entity\TrackingUnitLocate;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\TrackingUnitLocate as TrackingUnitLocateEntity;
use AppBundle\Form\Backend\TrackingUnitLocate as TrackingUnitLocateForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrackingUnitLocateController extends BaseController
{
    /**
     * @Route("/tracking_unit_locate/create", name="app_tracking_unit_locate_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function createAction(Request $request){
        $object = new TrackingUnitLocateEntity();

        $form = $this->generateForm(TrackingUnitLocateForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_tracking_unit_locate_list');
        }

        return $this->outputForm($form,'tracking_unit_locate/create.html.twig',array('page_title'=>'tracking_unit_locate_info'));
    }

    /**
     * @Route("/tracking_unit_locate/{id}/edit", name="app_tracking_unit_locate_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:TrackingUnitLocate');
        $object = $repo->find($id);

        $form = $this->generateForm(TrackingUnitLocateForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_tracking_unit_locate_list');
        }

        return $this->outputForm($form,'tracking_unit_locate/create.html.twig',array('page_title'=>'tracking_unit_locate_info'));
    }

    /**
     * @Route("/tracking_unit_locate/{id}/delete", name="app_tracking_unit_locate_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:TrackingUnitLocate');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_tracking_unit_locate_list');
    }

    /**
     * @Route("/tracking_unit_locate/list", name="app_tracking_unit_locate_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){

        $result = $this->get('app.mife')->getILocatorManager()->getCurrentLocation('766117164');
        var_dump($result[0]->name);
        exit();

//        $em = $this->getDoctrine()->getManager();
//        $repo = $em->getRepository('AppBundle:TrackingUnitLocate');
//        $form = $this->generateForm(TrackingUnitLocateForm::class,null,'search');
//
//        $qb = $repo->backendListSearch($form,$request);
//        $qb->andWhere('object.status=1');
//        $list = $qb->getQuery()->getResult();
//
//        return $this->outputList($list,'tracking_unit_locate/list.html.twig',$form,
//            array('page_title'=>'tracking_unit_locate_info')
//        );
    }
}