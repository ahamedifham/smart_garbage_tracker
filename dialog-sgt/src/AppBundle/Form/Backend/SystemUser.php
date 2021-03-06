<?php

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SystemUser extends Base
{

    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'system_user_info_system_user_name'))
            ->add('username',null,array('label'=>'system_user_info_system_user_username'))
            ->add('password','password',array('label'=>'system_user_info_system_user_password'))
            ->add('passwordConfirm','password',array('label'=>'system_user_info_system_user_password_confirm','mapped'=>false))
            ->add('systemUserRole',null,array('label'=>'system_user_info_system_user_role'))
            ->add('ownerCompany',null,array('label'=>'system_user_info_owner_company'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }
    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'system_user_info_system_user_name'))
            ->add('username',null,array('label'=>'system_user_info_system_user_username'))
            ->add('password','password',array('label'=>'system_user_info_system_user_password'))
            ->add('passwordConfirm','password',array('label'=>'system_user_info_system_user_password_confirm','mapped'=>false))
            ->add('systemUserRole',null,array('label'=>'system_user_info_system_user_role'))
            ->add('ownerCompany',null,array('label'=>'system_user_info_owner_company'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))

        ;
    }
    public function searchForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'system_user_info_system_user_name','required'=>false))
            ->add('username',null,array('label'=>'system_user_info_system_user_username','required'=>false))
            ->add('systemUserRole',null,array('label'=>'system_user_info_system_user_role','required'=>false))
            ->add('ownerCompany',null,array('label'=>'system_user_info_owner_company','required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SystemUser',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_system_user';
    }
}
