<?php

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MassageType extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('medium',null,array('label'=>'message_type_info_message_type_medium'))
            ->add('category',null,array('label'=>'message_type_info_message_type_category'))
//            ->add('successor',null,array('label'=>'message_type_info_message_type_contact'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('medium',null,array('label'=>'message_type_info_message_type_medium'))
            ->add('category',null,array('label'=>'message_type_info_message_type_category'))
//            ->add('successor',null,array('label'=>'message_type_info_message_type_contact'))
            ->add('save', SubmitType::class, array('label' => 'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('medium',null,array('label'=>'message_type_info_message_type_medium'))
            ->add('category',null,array('label'=>'message_type_info_message_type_contact'))
//            ->add('successor',null,array('label'=>'message_type_info_message_type_contact'))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MassageType',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_massage_type';
    }
}
