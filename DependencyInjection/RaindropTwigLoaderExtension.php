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
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Configuration logic takes place in here:
        // detach twig filesystem loader, attach twig chain loader and
        // append others loaders.
        if ($config['chain']['replace_twig_loader']) {
            $container->setAlias('twig.chain_loader', 'twig.loader');
        }

        // add the loaders defined in the configuration mapping
        $twigChainLoader = $container->getDefinition('twig.loader');

        // since twig chain loader doesn't feature priority order,
        // sort before appending loaders.
        $loaders = $this->sortLoaders($config);

        foreach ($loaders as $array) {
            foreach ($array as $twigLoader) {
                $twigChainLoader->addMethodCall('addLoader', array(new Reference($twigLoader)));
            }
        }
    }

    protected function sortLoaders($config) {
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
