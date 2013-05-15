<?php

namespace Raindrop\TwigLoaderBundle\Tests\DependencyInjection;

use Raindrop\TwigLoaderBundle\DependencyInjection\RaindropTwigLoaderExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RaindropTwigLoaderExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param  array                                                   $config
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected function getBuilder(array $config = array())
    {
        $builder = new ContainerBuilder();

        $loader = new RaindropTwigLoaderExtension();
        $loader->load($config, $builder);

        return $builder;
    }

    public function testLoadDefault()
    {
        $builder = $this->getBuilder();

        $this->assertTrue($builder->hasDefinition('raindrop_twig.loader.database'));
    }
}
