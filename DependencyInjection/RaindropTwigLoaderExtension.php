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

        if (class_exists('Sonata\\AdminBundle\\Admin\\Admin')) {
            $loader->load('admin.xml');
        }

        return;
    }

    protected function sortLoaders($config)
    {
        $loaders = array();

        foreach ($config['chain']['loaders_by_id'] as $name => $priority) {
            if (!isset($loaders[$priority])) {
                $loaders[$priority] = array();
            }

            $loaders[$priority] []= $name;
        }

        ksort($loaders);

        return $loaders;
    }
}
