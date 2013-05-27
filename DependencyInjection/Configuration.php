<?php

namespace Raindrop\TwigLoaderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('raindrop_twig_loader"')
            ->children()
                ->scalarNode('boost')
                    ->defaultFalse()
                ->end()
                ->arrayNode('chain')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('loaders_by_id')
                            ->defaultValue(array(
                                'raindrop_twig.loader.filesystem' => 10,
                                'raindrop_twig.loader.database' => 20
                            ))
                            ->useAttributeAsKey('id')
                            ->prototype('scalar')->end()
                        ->end()
                        ->scalarNode('replace_twig_loader')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
