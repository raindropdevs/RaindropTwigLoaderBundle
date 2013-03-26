<?php

namespace Raindrop\TwigLoaderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * CompilerPass Class for LiipThemeBundle inject
 */
class ThemesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('liip_theme.active_theme')) {
            return;
        }

        // inject liip_theme.active_theme service if present
        $definition = $container->getDefinition('raindrop_twig.loader.database');
        $definition->addMethodCall('addLiipTheme', array(new Reference('liip_theme.active_theme')));
    }
}
