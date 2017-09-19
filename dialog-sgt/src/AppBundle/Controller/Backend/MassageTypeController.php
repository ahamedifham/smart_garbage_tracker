<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Massage;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\MassageType;
use AppBundle\Form\Backend\MassageType as MassageTypeForm;
use Symfony\Component\Routing\Annotation\Route;
class MassageTypeController extends BaseController
{
    /**
     * @Route("/massage_type/create", name="app_massage_type_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request){
        $object = new MassageType();
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Languages');
        $languages=$repo->findAll();

        $form = $this->generateForm(MassageTypeForm::class,$object,'create');
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $object = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            foreach ($request->get('msgContents') as $index=>$msg){
                $massage = new Massage();
                $language = $languages[$index];
                $massage->setMassage($msg)
                    ->setLanguage($language)
                    ->setMassageType($object)
                ;
                $em->persist($massage);
            }
            $em->flush();
            return $this->redirectToRoute('app_massage_type_list');
        }
        return $this->outputForm($form,'massage_type/create.html.twig',array('page_title'=>'Message Type Info','languages'=>$languages));
    }

    /**
     * @Route("/massage_type/{id}/edit", name="app_massage_type_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:MassageType');
        $object=$repo->find($id);
        $repo = $em->getRepository('AppBundle:Languages');
        $languages=$repo->findAll();
        $repo = $em->getRepository('AppBundle:Massage');
        $massages=$repo->findBy(array('massageType'=>$object),array('id'=>'ASC'));

        $form = $this->generateForm(MassageTypeForm::class,$object,'edit');

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $object = $form->getData();
            $em->persist($object);
            $em->flush();
            foreach ($request->get('msgContents') as $index=>$msg){

                $language = $languages[$index];
                $massages[$index]->setMassage($msg)
                    ->setLanguage($language)
                    ->setMassageType($object)
                ;
                $em->persist($massages[$index]);
            }
            $em->flush();
            return $this->redirectToRoute('app_massage_type_list');
        }

        return $this->outputForm( $form,'massage_type/create.html.twig',array('page_title'=>'Message Type Info','languages'=>$languages,'massages'=>$massages));
    }

    /**
     * @Route("/massage_type/{id}/delete", name="app_massage_type_delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:MassageType');
        $commonUserObject = $repo->find($id);
        $commonUserObject ->setStatus(false);
        $em->flush($commonUserObject );
        return $this->redirectToRoute('app_massage_type_list');
    }

    /**
     * @Route("/massage_type/list", name="app_massage_type_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:MassageType');
        $form = $this->generateForm(MassageTypeForm::class,null,'search');

        $qb = $repo->backendListSearch($form,$request);
        $qb->andWhere('object.status=1');
        $list = $qb->getQuery()->getResult();

        return $this->outputList($list,'massage_type/list.html.twig',$form,
            array('page_title'=>'Message Type Info')
        );
    }
}

