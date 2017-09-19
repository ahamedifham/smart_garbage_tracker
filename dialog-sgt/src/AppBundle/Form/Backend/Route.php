<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 8/22/16
 * Time: 4:48 PM
 */

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class Route extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder//->add('code',null,array('label'=>'route_info_route_code'))
            ->add('name',null,array('label'=>'route_info_route_name'))
//            ->add('startPointLat',null,array('label'=>'route_info_route_startPointLat'))
//            ->add('startPointLng',null,array('label'=>'route_info_route_startPointLng'))
//            ->add('endPointLat',null,array('label'=>'route_info_route_endPointLat'))
//            ->add('endPointLng',null,array('label'=>'route_info_route_route_endPointLng'))
            //->add('routeId',null,array('label'=>'route_info_route_id'))
//            ->add('startPoint',null,array('label'=>'route_info_route_start_point'))
//            ->add('endPoint',null,array('label'=>'route_info_route_end_point'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder//->add('code',null,array('label'=>'route_info_route_code'))
            ->add('name',null,array('label'=>'route_info_route_name'))
//            ->add('startPointLat',null,array('label'=>'route_info_route_startPointLat'))
//            ->add('startPointLng',null,array('label'=>'route_info_route_startPointLng'))
//            ->add('endPointLat',null,array('label'=>'route_info_route_endPointLat'))
//            ->add('endPointLng',null,array('label'=>'route_info_route_route_endPointLng'))
            //->add('routeId',null,array('label'=>'route_info_route_id'))
//            ->add('startPoint',null,array('label'=>'route_info_route_start_point'))
//            ->add('endPoint',null,array('label'=>'route_info_route_end_point'))
            ->add('save', SubmitType::class, array('label' => 'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('code',null,array('label'=>'route_info_route_code','required'=>false))
            ->add('name',null,array('label'=>'route_info_route_name', 'required'=>false))
            ->add('routeId',null,array('label'=>'route_info_route_id', 'required'=>false))
//            ->add('startPoint',null,array('label'=>'route_info_route_start_point'))
//            ->add('endPoint',null,array('label'=>'route_info_route_end_point'))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Route',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_route';
    }
}
