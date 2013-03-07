<?php

namespace Raindrop\TwigLoaderBundle\Tests\Loader;


use Symfony\Component\HttpFoundation\Request;

use Raindrop\TwigLoaderBundle\Loader\ChainLoader;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Raindrop\TwigLoaderBundle\Tests\BaseTestCase;

class ChainLoaderTest extends BaseTestCase
{
    public function testProxyMethods() {
        $chainLoader = new ChainLoader;

        $fileSystemLoader = $this
                ->getMockBuilder('Symfony\\Bundle\\TwigBundle\Loader\\FilesystemLoader')
                ->disableOriginalConstructor()
                ->getMock()
                ;

        $fileSystemLoader
                ->expects($this->exactly(2))
                ->method('addPath')
                ;
        $fileSystemLoader
                ->expects($this->once())
                ->method('setPaths')
//                ->with($this->equalTo(array('tic'), 'ns'))
                ;
        $fileSystemLoader
                ->expects($this->once())
                ->method('prependPath')
//                ->with($this->equalTo(array('tac'), 'ns2'))
                ;

        $chainLoader->addLoader($fileSystemLoader);
        $chainLoader->addPath('foo');
        $chainLoader->addPaths(array('bar'));
        $chainLoader->setPaths(array('tic'), 'ns');
        $chainLoader->prependPath(array('tac'), 'ns2');
    }
}
