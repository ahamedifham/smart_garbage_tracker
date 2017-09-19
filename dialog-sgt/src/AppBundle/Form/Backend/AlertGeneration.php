<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/16/16
 * Time: 9:55 AM
 */

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AlertGeneration extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('type',null,array('label'=>'alert_generation_info_alert_generation_type'))
            ->add('message',null,array('label'=>'alert_generation_info_alert_generation_message'))
            ->add('save',SubmitType::class,array('label'=>'send_button'))
        ;

    }
    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('type',null,array('label'=>'alert_generation_info_alert_generation_type'))
            ->add('message',null,array('label'=>'alert_generation_info_alert_generation_message'))
            ->add('save',SubmitType::class,array('label'=>'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('type',null,array('label'=>'alert_generation_info_alert_generation_type'))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>'AppBundle\Entity\AlertGeneration',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_alert_generation';
    }
    
    
}