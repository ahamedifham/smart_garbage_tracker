<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/27/16
 * Time: 10:17 AM
 */

namespace AppBundle\Controller\Backend;
use AppBundle\Entity\CommonUserFeedback;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\CommonUserFeedback as CommonUserFeedbackEntity;
use AppBundle\Form\Backend\CommonUserFeedback as CommonUserFeedbackForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommonUserFeedbackController extends BaseController
{
    /**
     * //@Route("/common_user_feedback/create", name="app_common_user_feedback_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function createAction(Request $request){
        $object = new CommonUserFeedbackEntity();

        $form = $this->generateForm(CommonUserFeedbackForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_common_user_feedback_list');
        }

        return $this->outputForm($form,'common_user_feedback/create.html.twig',array('page_title'=>'common_user_feedback_info'));
    }

    /**
     * @Route("/common_user_feedback/{id}/edit", name="app_common_user_feedback_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:CommonUserFeedback');
        $object = $repo->find($id);

        $form = $this->generateForm(CommonUserFeedbackForm::class,$object,'edit');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('app_common_user_feedback_list');
        }

        return $this->outputForm($form,'common_user_feedback/create.html.twig',array('page_title'=>'common_user_feedback_info'));
    }

    /**
     * @Route("/common_user_feedback/{id}/delete", name="app_common_user_feedback_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:CommonUserFeedback');
        $object = $repo->find($id);
        $object->setStatus(false);
        $em->flush($object);
        return $this->redirectToRoute('app_common_user_feedback_list');
    }

    /**
     * @Route("/common_user_feedback/list", name="app_common_user_feedback_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:CommonUserFeedback');
        $form = $this->generateForm(CommonUserFeedbackForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list,'common_user_feedback/list.html.twig',$form,
            array('page_title'=>'common_user_feedback_info')
        );
    }
}