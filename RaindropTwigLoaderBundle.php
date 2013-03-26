<?php

namespace Raindrop\TwigLoaderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Raindrop\TwigLoaderBundle\DependencyInjection\Compiler\ThemesCompilerPass;

class RaindropTwigLoaderBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ThemesCompilerPass());
    }
}
