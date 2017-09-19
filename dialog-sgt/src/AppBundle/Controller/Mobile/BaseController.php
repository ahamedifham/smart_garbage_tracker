<?php

namespace AppBundle\Controller\Mobile;
/**
 * Created by PhpStorm.
 * User: Isuru
 * Date: 8/5/2016
 * Time: 11:33 AM
 */
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    /**
     * Generate form with different form options
     * @param $namespace
     * @param $object
     * @param $mode : create,edit,search
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    protected function generateForm($namespace,$object,$mode,$options = array()){
        switch ($mode){
            case 'create':
                return $this->createForm($namespace,$object,array_merge(array('mode'=>'create','translation_domain' => 'backend','attr'=>array('id'=>'data_entry_form')),$options));
            case 'edit':
                return $this->createForm($namespace,$object,array_merge(array('mode'=>'edit','translation_domain' => 'backend','attr'=>array('id'=>'data_entry_form')),$options));
            case 'search':
                return $this->createForm($namespace,$object,array_merge(array('mode'=>'search','translation_domain' => 'backend','method'=>'GET','attr'=>array('novalidate'=>'novalidate')),$options));
            default:
                return $this->createForm($namespace,$object,$options);
        }
    }

    /**
     * @param $form
     * @param $template
     * @param $pageTexts
     * @return Response
     */
    protected function outputForm($form,$template,$pageTexts){
        $variablePassArr = array(
            'form' => $form->createView(),
        );
        $variablePassArr = array_merge($pageTexts,$variablePassArr);
        return $this->render('backend/'.$template, $variablePassArr);
    }

    /**
     * @param $list
     * @param $template
     * @param array $pageTexts
     * @param null $searchForm
     * @return Response
     */
    protected function outputList($list,$template,$searchForm=null,$pageTexts=array()){
        $variablePassArr = isset($searchForm) ? array(
            'searchForm' => $searchForm->createView(),
            'list'=>$list
        ) : array('list'=>$list);
        $variablePassArr = array_merge($pageTexts,$variablePassArr);
        return $this->render('backend/'.$template, $variablePassArr);
    }
}