<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/21/16
 * Time: 4:01 PM
 */

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Schedule extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'schedule_info_schedule_name'))
        ->add('driver',null,array('label'=>'schedule_info_schedule_driver'))
            ->add('route',null,array('label'=>'schedule_info_schedule_route'))
            ->add('truck',null,array('label'=>'schedule_info_schedule_truck'))
            ->add('supervisor',null,array('label'=>'schedule_info_schedule_supervisor'))
            ->add('description',null,array('label'=>'schedule_info_schedule_description'))
            ->add('date',null,array('label'=>'schedule_info_schedule_date'))
            ->add('weekday',null,array('label'=>'schedule_info_schedule_weekday'))
            ->add('startTime',null,array('label'=>'schedule_info_schedule_start_time'))
            ->add('endTime',null,array('label'=>'schedule_info_schedule_end_time'))
            ->add('repeat1',null,array('label'=>'schedule_info_schedule_repeat'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'schedule_info_schedule_name'))
            ->add('driver',null,array('label'=>'schedule_info_schedule_driver'))
            ->add('route',null,array('label'=>'schedule_info_schedule_route'))
            ->add('truck',null,array('label'=>'schedule_info_schedule_truck'))
            ->add('supervisor',null,array('label'=>'schedule_info_schedule_supervisor'))
            ->add('description',null,array('label'=>'schedule_info_schedule_description'))
            ->add('date',null,array('label'=>'schedule_info_schedule_date'))
            ->add('weekday',null,array('label'=>'schedule_info_schedule_weekday'))
            ->add('startTime',null,array('label'=>'schedule_info_schedule_start_time'))
            ->add('endTime',null,array('label'=>'schedule_info_schedule_end_time'))
            ->add('repeat1',null,array('label'=>'schedule_info_schedule_repeat'))
            ->add('save', SubmitType::class, array('label' => 'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('date',null,array('label'=>'schedule_info_schedule_date'))
//            ->add('name',null,array('label'=>'schedule_info_schedule_name', 'required'=>false))
//            ->add('driver',null,array('label'=>'schedule_info_schedule_driver', 'required'=>false))
//            ->add('route',null,array('label'=>'schedule_info_schedule_route', 'required'=>false))
//            ->add('truck',null,array('label'=>'schedule_info_schedule_truck', 'required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Schedule',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_schedule';
    }
}