<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/8/16
 * Time: 7:38 PM
 */

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrackingUnitLocate extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_name'))
            ->add('route',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_route'))
            ->add('truck',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_truck'))
            ->add('lat',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_lat'))
            ->add('lng',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_lng'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_name'))
            ->add('route',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_route'))
            ->add('truck',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_truck'))
            ->add('lat',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_lat'))
            ->add('lng',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_lng'))
            ->add('save', SubmitType::class, array('label' => 'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('name',null,array('label'=>'tracking_unit_locate_info_tracking_unit_locate_name','required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TrackingUnitLocate',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_tracking_unit_locate';
    }
}