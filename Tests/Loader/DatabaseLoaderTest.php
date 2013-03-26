<?php

namespace Raindrop\TwigLoaderBundle\Tests\Loader;

use Raindrop\TwigLoaderBundle\Loader\DatabaseTwigLoader;
use Raindrop\TwigLoaderBundle\Tests\BaseTestCase;

class DatabaseTwigLoaderTest extends BaseTestCase
{
    protected function getDbLoader($parameter = 'test', $return = true, $theme = false)
    {
        $recordStub = new RecordStub;

        $returnValue = false;
        if ($return) {
            $returnValue = $recordStub;
        }

        if ($theme) {
            $parameter.= '|'.$recordStub->getActiveTheme();
        }

        $entityManager = $this
                ->getMockBuilder('\\Doctrine\\ORM\\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();

        $entityRepository = $this
                ->getMockBuilder('\\Doctrine\\ORM\\EntityRepository')
                ->setMethods(array('findOneByName'))
                ->disableOriginalConstructor()
                ->getMock();

        $entityManager
                ->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValue($entityRepository));

        $entityRepository
                ->expects($this->once())
                ->method('findOneByName')
                ->with($this->equalTo($parameter))
                ->will($this->returnValue($returnValue));

        $dbLoader = new DatabaseTwigLoader;
        $dbLoader->setEntityManager($entityManager);

        return $dbLoader;
    }

    public function testExists()
    {
        $dbLoader = $this->getDbLoader('test', true);
        $this->assertTrue($dbLoader->exists('database:test'));
    }

    public function testDoesNotExists()
    {
        $dbLoader = $this->getDbLoader('no_test', false);
        $this->assertNull($dbLoader->exists('database:no_test'));
    }

    public function testExistsWithTheme()
    {
        $dbLoader = $this->getDbLoader('test', true, true);
        $this->assertTrue($dbLoader->exists('database:test|desktop'));
    }

    /**
     * There is test for 'getSource' method failure
     * because it depends on 'exists' method
     */
    public function testGetSource()
    {
        $dbLoader = $this->getDbLoader('test', true);
        $this->assertEquals('<p>Hello {{ user }}</p>', $dbLoader->getSource('database:test'));
    }

    /**
     * Trivial, for coverage sake.
     */
    public function testGetCacheKey()
    {
       $dbLoader = new DatabaseTwigLoader;
       $this->assertEquals('my_cache_key', $dbLoader->getCacheKey('my_cache_key'));
    }

    public function testIsFresh()
    {
        // unix epoch
        $longLongTimeAgoInAGalaxyFarAway = 0;
        $backToTheFuture = time() + 86400;

        $dbLoader = $this->getDbLoader('test', true);

        /**
         * First call to dbLoader populates cache
         */
        $this->assertFalse($dbLoader->isFresh('database:test', $longLongTimeAgoInAGalaxyFarAway));
        $this->assertTrue($dbLoader->isFresh('database:test', $backToTheFuture));
    }
}

class RecordStub
{
    public function getName()
    {
        return 'stub_tpl';
    }

    public function getUpdated()
    {
        return new \DateTime;
    }

    public function getTemplate()
    {
        return '<p>Hello {{ user }}</p>';
    }

    public function getActiveTheme()
    {
        return 'desktop';
    }
}
