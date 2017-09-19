<?php
/**
 * Created by PhpStorm.
 * User: ifham
 * Date: 11/3/16
 * Time: 2:23 PM
 */

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SmartBin extends Base
{
    public function createForm(FormBuilderInterface $builder)
    {
        $builder->add('serial',null,array('label'=>'smart_bin_info_smart_bin_serial'))
            ->add('route',null,array('label'=>'smart_bin_info_smart_bin_route'))
           //->add('smartBinBinLevelEvent',null,array('label'=>'smart_bin_info_smart_bin_smart_bin_bin_level_event'))
            //->add('smartBinBatLevelEvent',null,array('label'=>'smart_bin_info_smart_bin_smart_bin_bat_level_event'))
           //->add('batteryLevel',null,array('label'=>'smart_bin_info_smart_bin_battery_level'))
           // ->add('binLevel',null,array('label'=>'smart_bin_info_smart_bin_bin_level'))
            ->add('user',null,array('label'=>'smart_bin_info_smart_bin_bin_user'))
            ->add('userEmail',null,array('label'=>'smart_bin_info_smart_bin_bin_user_email'))
            ->add('userMobileOne',null,array('label'=>'smart_bin_info_smart_bin_bin_user_mobile_one'))
            ->add('userMobileTwo',null,array('label'=>'smart_bin_info_smart_bin_bin_user_mobile_two'))
            ->add('userMobileThree',null,array('label'=>'smart_bin_info_smart_bin_bin_user_mobile_three'))
            ->add('userAddress',null,array('label'=>'smart_bin_info_smart_bin_bin_user_address'))
            ->add('description',null,array('label'=>'smart_bin_info_smart_bin_bin_description'))
            ->add('save',SubmitType::class,array('label'=>'create_button'))
        ;
    }

    public function editForm(FormBuilderInterface $builder)
    {
        $builder->add('serial',null,array('label'=>'smart_bin_info_smart_bin_serial'))
            ->add('route',null,array('label'=>'smart_bin_info_smart_bin_route'))
           // ->add('smartBinBinLevelEvent',null,array('label'=>'smart_bin_info_smart_bin_smart_bin_bin_level_event'))
             //->add('smartBinBatLevelEvent',null,array('label'=>'smart_bin_info_smart_bin_smart_bin_bat_level_event'))
            // ->add('batteryLevel',null,array('label'=>'smart_bin_info_smart_bin_battery_level'))
            // ->add('binLevel',null,array('label'=>'smart_bin_info_smart_bin_bin_level'))
            ->add('user',null,array('label'=>'smart_bin_info_smart_bin_bin_user'))
            ->add('userEmail',null,array('label'=>'smart_bin_info_smart_bin_bin_user_email'))
            ->add('userMobileOne',null,array('label'=>'smart_bin_info_smart_bin_bin_user_mobile_one'))
            ->add('userMobileTwo',null,array('label'=>'smart_bin_info_smart_bin_bin_user_mobile_two'))
            ->add('userMobileThree',null,array('label'=>'smart_bin_info_smart_bin_bin_user_mobile_three'))
            ->add('userAddress',null,array('label'=>'smart_bin_info_smart_bin_bin_user_address'))
            ->add('description',null,array('label'=>'smart_bin_info_smart_bin_bin_description'))
            ->add('save', SubmitType::class, array('label' => 'save_button'))
        ;
    }

    public function searchForm(FormBuilderInterface $builder){
        $builder->add('serial',null,array('label'=>'smart_bin_info_smart_bin_serial','required'=>false))
            ->add('route',null,array('label'=>'smart_bin_info_smart_bin_route','required'=>false))
            ->add('search',SubmitType::class,array('label'=>'search_button'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\SmartBin',
            'mode'=>null
        ));
    }

    public function getName()
    {
        return 'app_bundle_smart_bin';
    }
}