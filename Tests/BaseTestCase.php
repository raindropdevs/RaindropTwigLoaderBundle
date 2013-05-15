<?php

namespace Raindrop\TwigLoaderBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestCase extends WebTestCase
{
    protected function buildMock($class, array $methods = array())
    {
        return $this->getMockBuilder($class)
                ->disableOriginalConstructor()
                ->setMethods($methods)
                ->getMock();
    }

    /**
     * Dunno why this class is giving me errors :[
     */
    public function testDummy() {
    	$this->assertTrue(true);
    }
}
