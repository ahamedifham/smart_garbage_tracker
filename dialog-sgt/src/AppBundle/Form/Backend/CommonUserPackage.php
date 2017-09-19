<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/15/16
 * Time: 1:30 PM
 */

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CommonUserPackage extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'common_user_package_info_common_user_package_name'))
            ->add('description',null,array('label'=>'common_user_package_info_common_user_package_description'))
            ->add('fee',null,array('label'=>'common_user_package_info_common_user_package_fee'))
            ->add('taxPercentage',null,array('label'=>'common_user_package_info_common_user_package_tax_percentage'))
            ->add('frequency',null,array('label'=>'common_user_package_info_common_user_package_frequency'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;

    }
    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('name',null,array('label'=>'common_user_package_info_common_user_package_name'))
            ->add('description',null,array('label'=>'common_user_package_info_common_user_package_description'))
            ->add('fee',null,array('label'=>'common_user_package_info_common_user_package_fee'))
            ->add('taxPercentage',null,array('label'=>'common_user_package_info_common_user_package_taxPercentage'))
            ->add('frequency',null,array('label'=>'common_user_package_info_common_user_package_frequency'))
            ->add('save',SubmitType::class,array('label'=>'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('name',null,array('label'=>'common_user_package_info_common_user_package_name','required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>'AppBundle\Entity\CommonUserPackage',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_common_user_package';
    }

}