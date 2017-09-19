<?php

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommonUser extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'common_user_info_common_user_name'))
                ->add('contact',null,array('label'=>'common_user_info_common_user_contact'))
                ->add('email',null,array('label'=>'common_user_info_common_user_email'))
                ->add('subscriberId',null,array('label'=>'common_user_info_common_user_subscriber_id'))
//                ->add('username',null,array('label'=>'common_user_info_common_user_username'))
//                ->add('password','password',array('label'=>'common_user_info_common_user_password'))
//                ->add('passwordConfirm','password',array('label'=>'common_user_info_common_user_password_confirm','mapped'=>false))
//                ->add('commonUserPackage',null,array('label'=>'common_user_info_common_user_package'))
                ->add('route',null,array('label'=>'common_user_info_route_info'))
                ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;

    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'common_user_info_common_user_name'))
                ->add('contact',null,array('label'=>'common_user_info_common_user_contact'))
                ->add('email',null,array('label'=>'common_user_info_common_user_email', 'disabled' => true))
                ->add('subscriberId',null,array('label'=>'common_user_info_common_user_subscriber_id'))
//                ->add('username',null,array('label'=>'common_user_info_common_user_username'))
//                ->add('password','password',array('label'=>'common_user_info_common_user_password'))
//                ->add('passwordConfirm','password',array('label'=>'common_user_info_common_user_password_confirm','mapped'=>false))
//                ->add('commonUserPackage',null,array('label'=>'common_user_info_common_user_package'))
                ->add('route',null,array('label'=>'common_user_info_route_info'))
                ->add('save',SubmitType::class,array('label'=>'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('name',null,array('label'=>'common_user_info_common_user_name','required'=>false))
            ->add('username',null,array('label'=>'common_user_info_common_user_username','required'=>false))
            ->add('contact',null,array('label'=>'common_user_info_common_user_contact','required'=>false))
            ->add('email',null,array('label'=>'common_user_info_common_user_email','required'=>false))
            //->add('subscriberId',null,array('label'=>'common_user_info_common_user_subscriber_id','required'=>false))
            ->add('commonUserPackage',null,array('label'=>'common_user_info_common_user_package','required'=>false))
            ->add('route',null,array('label'=>'common_user_info_route_info','required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' =>'AppBundle\Entity\CommonUser',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_common_user';
    }
}
