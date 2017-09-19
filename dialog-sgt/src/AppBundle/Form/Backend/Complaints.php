<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/9/16
 * Time: 4:25 AM
 */

namespace AppBundle\Form\Backend;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class Complaints extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('commonUser',null,array('label'=>'complaints_info_complaints_common_user'))
            ->add('date',null,array('label'=>'complaints_info_complaints_date'))
            ->add('time',null,array('label'=>'complaints_info_complaints_time'))
            ->add('description',null,array('label'=>'complaints_info_complaints_description'))
           // ->add('isRead',null,array('label'=>'complaints_info_complaints_is_read'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('commonUser',null,array('label'=>'complaints_info_complaints_common_user'))
            ->add('date',null,array('label'=>'complaints_info_complaints_date'))
            ->add('time',null,array('label'=>'complaints_info_complaints_time'))
            ->add('description',null,array('label'=>'complaints_info_complaints_description'))
            // ->add('isRead',null,array('label'=>'complaints_info_complaints_is_read'))
            ->add('save', SubmitType::class, array('label' => 'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('commonUser',null,array('label'=>'complaints_info_complaints_common_user','required'=>false))
            ->add('id',null,array('label'=>'complaints_info_complaints_id', 'required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Complaints',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_complaints';
    }
}