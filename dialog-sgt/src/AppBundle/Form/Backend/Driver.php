<?php

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Driver extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'driver_info_driver_name'))
            ->add('contact',null,array('label'=>'driver_info_driver_contact'))
            ->add('subscriberId',null,array('label'=>'driver_info_driver_subscriber_id'))
            //->add('username',null,array('label'=>'driver_info_driver_username'))
            //->add('password','password',array('label'=>'driver_info_driver_password'))
            //->add('passwordConfirm','password',array('label'=>'driver_info_driver_password_confirm','mapped'=>false))
            ->add('ownerCompany',null,array('label'=>'driver_info_owner_company'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('name', null, array('label' => 'driver_info_driver_name'))
            ->add('contact', null, array('label' => 'driver_info_driver_contact'))
            ->add('subscriberId', null, array('label' => 'driver_info_driver_subscriber_id'))
            //->add('username', null, array('label' => 'driver_info_driver_username'))
            //->add('password', 'password', array('label' => 'driver_info_driver_password'))
            //->add('passwordConfirm','password',array('label'=>'driver_info_driver_password_confirm','mapped'=>false))
            ->add('ownerCompany', null, array('label' => 'driver_info_owner_company'))
            ->add('save', SubmitType::class, array('label' => 'save_button'));
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('name', null, array('label' => 'driver_info_driver_name','required'=>false))
            ->add('contact', null, array('label' => 'driver_info_driver_contact','required'=>false))
            ->add('subscriberId', null, array('label' => 'driver_info_driver_subscriber_id','required'=>false))
            //->add('username', null, array('label' => 'driver_info_driver_username'))
            ->add('ownerCompany', null, array('label' => 'driver_info_owner_company','required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Driver',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_driver';
    }
}
