<?php

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Form\Backend\Vehicle;

class TrackingUnit extends Base
{

    public function createForm(FormBuilderInterface $builder){
        $builder->add('msisdn',null,array('label'=>'tracker_info_tracker_id'))
            ->add('vehicle',Vehicle::class)
            ->add('driver',null,array('label'=>'tracker_info_driver'))
            ->add('route',null,array('label'=>'tracker_info_route_info'))
            ->add('ownerCompany',null,array('label'=>'tracker_info_owner_company'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder){
        $builder->add('msisdn',null,array('label'=>'tracker_info_tracker_id'))
            ->add('vehicle',Vehicle::class)

            ->add('driver',null,array('label'=>'tracker_info_driver'))
            ->add('route',null,array('label'=>'tracker_info_route_info'))
            ->add('ownerCompany',null,array('label'=>'tracker_info_owner_company'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('msisdn',null,array('label'=>'tracker_info_tracker_id','required'=>false))
            ->add('vehicle',null,array('label'=>'tracker_info_vehicle','required'=>false))
            ->add('driver',null,array('label'=>'tracker_info_driver','required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TrackingUnit',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_tracking_unit';
    }
}
