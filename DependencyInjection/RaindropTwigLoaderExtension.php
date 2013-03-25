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

        /**
         * Configuration logic takes place in here:
         * detach twig filesystem loader, attach twig chain loader and
         * append others loaders.
         */
        if ($config['chain']['replace_twig_loader']) {

            $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('services.xml');

            if (class_exists('Sonata\\AdminBundle\\Admin\\Admin')) {
                $loader->load('admin.xml');
            }

            /**
             * Set some references to let other bundles hook to the proper
             * loader.
             */
            $container->setAlias('twig.loader', 'raindrop_twig.loader.chain');
            $container->setAlias('twig.loader.filesystem', 'raindrop_twig.loader.filesystem');

            /**
             * Add the loaders defined in the configuration mapping.
             * Since twig chain loader doesn't feature priority sorting,
             * sort them before appending.
             */
            $twigChainLoader = $container->getDefinition('raindrop_twig.loader.chain');
            $loaders = $this->sortLoaders($config);

            foreach ($loaders as $array) {
                foreach ($array as $twigLoader) {
                    $twigChainLoader->addMethodCall('addLoader', array(new Reference($twigLoader)));
                }
            }

            // @TODO bugfix: twig loader is not using default 'addMethodCall'
            // from symfony twig extension.
            // can't figure out why... knpmenubundle works properly
            $reflClass = new \ReflectionClass('Symfony\Bridge\Twig\Extension\FormExtension');
            $stdSfPath = dirname(dirname($reflClass->getFileName())).'/Resources/views/Form';
            $container
                ->getDefinition('raindrop_twig.loader.chain')
                ->addMethodCall('addPath', array($stdSfPath));
        }
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
