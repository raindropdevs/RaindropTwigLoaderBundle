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
        $this->assertTrue($builder->hasAlias('twig.loader'));
        $alias = $builder->getAlias('twig.loader');
        $this->assertEquals('raindrop_twig.loader.chain', $alias->__toString());

        $this->assertTrue($builder->hasDefinition('raindrop_twig.loader.chain'));
        $methodCalls = $builder->getDefinition('raindrop_twig.loader.chain')->getMethodCalls();

        $addMethodCalls = array_filter(
            $methodCalls,
            function ($call) {
                return 'addLoader' == $call[0] or 'addPath' == $call[0];
            }
        );

        $this->assertCount(3, $addMethodCalls);

        /** @var $reference \Symfony\Component\DependencyInjection\Reference */

        $method = $addMethodCalls[0][0];
        $reference = $addMethodCalls[0][1][0];
        $this->assertEquals('addLoader', $method);
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $reference);
        $this->assertEquals('raindrop_twig.loader.filesystem', $reference->__toString());

        $method = $addMethodCalls[1][0];
        $reference = $addMethodCalls[1][1][0];
        $this->assertEquals('addLoader', $method);
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $reference);
        $this->assertEquals('raindrop_twig.loader.database', $reference->__toString());

        $method = $addMethodCalls[2][0];
        $path = $addMethodCalls[2][1][0];
        $this->assertEquals('addPath', $method);
        $this->assertEquals(1, preg_match('_/Symfony/Bridge/Twig/Resources/views/Form$_', $path));
    }

    public function testLoadConfigured()
    {
        $config = array(
            array(
                'chain' => array(
                    'loaders_by_id' => $providedLoaders = array(
                        'loader.custom' => 10,
                        'loader.default' => 20
                    ),
                    'replace_twig_loader' => true
                )
            )
        );

        $builder = $this->getBuilder($config);

        $this->assertTrue($builder->hasAlias('twig.loader'));
        $alias = $builder->getAlias('twig.loader');
        $this->assertEquals('raindrop_twig.loader.chain', $alias->__toString());

        $this->assertTrue($builder->hasDefinition('raindrop_twig.loader.chain'));
        $methodCalls = $builder->getDefinition('raindrop_twig.loader.chain')->getMethodCalls();
        $addMethodCalls = array_filter(
            $methodCalls,
            function ($call) {
                return 'addLoader' == $call[0] or 'addPath' == $call[0];
            }
        );

        $this->assertCount(3, $addMethodCalls);

        $method = $addMethodCalls[0][0];
        $reference = $addMethodCalls[0][1][0];
        $this->assertEquals('addLoader', $method);
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $reference);
        $this->assertEquals('loader.custom', $reference->__toString());
        $loadersAdded [$reference->__toString()]= 10;

        $method = $addMethodCalls[1][0];
        $reference = $addMethodCalls[1][1][0];
        $this->assertEquals('addLoader', $method);
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $reference);
        $this->assertEquals('loader.default', $reference->__toString());
        $loadersAdded [$reference->__toString()]= 20;

        $method = $addMethodCalls[2][0];
        $path = $addMethodCalls[2][1][0];
        $this->assertEquals('addPath', $method);
        $this->assertEquals(1, preg_match('_/Symfony/Bridge/Twig/Resources/views/Form$_', $path));

        $this->assertEquals($providedLoaders, $loadersAdded);
    }

    public function testLoadNotActive()
    {
        $config = array(
            array(
                'chain' => array(
                    'loaders_by_id' => $providedLoaders = array(
                        'loader.custom' => 10,
                        'loader.default' => 20
                    ),
                    'replace_twig_loader' => false
                )
            )
        );

        $builder = $this->getBuilder($config);
        $this->assertFalse($builder->hasAlias('twig.loader'));
        $this->assertFalse($builder->hasDefinition('raindrop_twig.loader.chain'));
        $this->assertFalse($builder->hasDefinition('raindrop_twig.loader.filesystem'));
        $this->assertFalse($builder->hasDefinition('raindrop_twig.loader.database'));

    }

}
