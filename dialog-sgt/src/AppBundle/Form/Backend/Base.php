<?php
namespace AppBundle\Form\Backend;
/**
 * Created by PhpStorm.
 * User: Isuru
 * Date: 8/5/2016
 * Time: 11:29 AM
 */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class Base extends AbstractType
{
    protected $formOptions = array();
    /**
     * Basic form builder function
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(isset($options['mode']))
        {
            switch ($options['mode'])
            {
                case 'create':
                    $this->createForm($builder);
                    break;
                case 'edit':
                    $this->editForm($builder);
                    break;
                case 'search':
                    $this->searchForm($builder);
                    break;
            }
        }
    }

    /**
     * Create form builder function
     * @param FormBuilderInterface $builder
     */
    public function createForm(FormBuilderInterface $builder){
    }

    /**
     * Edit form builder function
     * @param FormBuilderInterface $builder
     */
    public function editForm(FormBuilderInterface $builder){
    }

    /**
     * Search form builder function
     * @param FormBuilderInterface $builder
     */
    public function searchForm(FormBuilderInterface $builder){
    }
}