<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 10/26/16
 * Time: 3:09 PM
 */

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class CommonUserFeedback extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('commonUser',null,array('label'=>'common_user_feedback_info_common_user_feedback_name'))
            ->add('rate',null,array('label'=>'common_user_feedback_info_common_user_feedback_rate'))
            ->add('feedback',null,array('label'=>'common_user_feedback_info_common_user_feedback_feedback'))
            ->add('date',null,array('label'=>'common_user_feedback_info_common_user_feedback_date'))
            ->add('time',null,array('label'=>'common_user_feedback_info_common_user_feedback_time'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('commonUser',null,array('label'=>'common_user_feedback_info_common_user_feedback_name'))
            ->add('rate',null,array('label'=>'common_user_feedback_info_common_user_feedback_rate'))
            ->add('feedback',null,array('label'=>'common_user_feedback_info_common_user_feedback_feedback'))
            ->add('date',null,array('label'=>'common_user_feedback_info_common_user_feedback_date'))
            ->add('time',null,array('label'=>'common_user_feedback_info_common_user_feedback_time'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('commonUser',null,array('label'=>'common_user_feedback_info_common_user_feedback_name','required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CommonUserFeedback',
            'mode'=>null
        ));
    }


    public function getName()
    {
        return 'app_bundle_common_user_feedback';
    }
}