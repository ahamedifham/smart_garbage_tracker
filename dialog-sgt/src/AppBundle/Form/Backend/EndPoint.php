<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 8/23/16
 * Time: 10:29 AM
 */

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class EndPoint extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'end_point_info_end_point_name'))
            ->add('description',null,array('label'=>'end_point_info_end_point_description'))
//            ->add('lat',null,array('label'=>'end_point_info_end_point_lat'))
//            ->add('lng',null,array('label'=>'end_point_info_end_point_lng'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'end_point_info_end_point_name'))
            ->add('description',null,array('label'=>'end_point_info_end_point_description'))
//            ->add('lat',null,array('label'=>'end_point_info_end_point_lat'))
//            ->add('lng',null,array('label'=>'end_point_info_end_point_lng'))
            ->add('save', SubmitType::class, array('label' => 'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('name',null,array('label'=>'end_point_info_end_point_name'))
//            ->add('lat',null,array('label'=>'end_point_info_end_point_lat'))
//            ->add('lng',null,array('label'=>'end_point_info_end_point_lng'))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EndPoint',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_end_point';
    }

}