<?php

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Vehicle extends Base
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('vehicleNo',null,array('label'=>'vehicle_info_vehicle_no'))
            ->add('vehicleBrand',null,array('label'=>'vehicle_info_vehicle_brand'))
            ->add('capacity',null,array('label'=>'vehicle_info_vehicle_capacity'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Vehicle',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_vehicle';
    }
}
