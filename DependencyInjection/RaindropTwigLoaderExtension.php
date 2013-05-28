<?php

namespace Raindrop\TwigLoaderBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RaindropTwigLoaderExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');


        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        /**
        * Setting up boost config
        */
        $container
            ->getDefinition('raindrop_twig.loader.database')
            ->addMethodCall('setBoost', array($config['boost']))
        ;

        if (class_exists('Sonata\\AdminBundle\\Admin\\Admin')) {
            $loader->load('admin.xml');
        }

        return;
    }
}
