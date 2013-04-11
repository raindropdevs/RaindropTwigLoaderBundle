<?php

namespace Raindrop\TwigLoaderBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TwigTemplateAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name', null, array('required' => true))
                ->add('type', null, array('required' => false))
                ->add('template', null, array(
                    'required' => true,
                    'attr' => array(
                        'class' => 'span12'
                    )
                ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('type')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->addIdentifier('type')
            ->addIdentifier('template', null, array(
                'template' => 'RaindropTwigLoaderBundle::admin_twig_template_list_item.html.twig'
            ))
            ->addIdentifier('updated')

            // add custom action links
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array()
                )
            ))
        ;
    }
}
