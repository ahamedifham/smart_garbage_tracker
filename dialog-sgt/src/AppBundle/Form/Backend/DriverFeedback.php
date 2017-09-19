<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/27/16
 * Time: 11:36 AM
 */

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DriverFeedback extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('driver',null,array('label'=>'driver_feedback_info_driver_feedback_name'))
            ->add('rate',null,array('label'=>'driver_feedback_info_driver_feedback_rate'))
            ->add('feedback',null,array('label'=>'driver_feedback_info_driver_feedback_feedback'))
            ->add('date',null,array('label'=>'driver_feedback_info_driver_feedback_date'))
            ->add('time',null,array('label'=>'driver_feedback_info_driver_feedback_time'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('driver',null,array('label'=>'driver_feedback_info_driver_feedback_name'))
            ->add('rate',null,array('label'=>'driver_feedback_info_driver_feedback_rate'))
            ->add('feedback',null,array('label'=>'driver_feedback_info_driver_feedback_feedback'))
            ->add('date',null,array('label'=>'driver_feedback_info_driver_feedback_date'))
            ->add('time',null,array('label'=>'driver_feedback_info_driver_feedback_time'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('driver',null,array('label'=>'driver_feedback_info_driver_feedback_name','required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DriverFeedback',
            'mode'=>null
        ));
    }


    public function getName()
    {
        return 'app_bundle_driver_feedback';
    }
}