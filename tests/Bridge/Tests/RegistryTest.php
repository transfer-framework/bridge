<?php

namespace Bridge\Tests;

use Bridge\Registry;
use Bridge\Service;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests add- and getService methods.
     */
    public function testAddGetService()
    {
        $registry = new Registry(new EventDispatcher());

        $service = new Service('test');
        $registry->addService($service);

        $this->assertNotNull($registry->getService('test'));
        $this->assertSame($service, $registry->getService('test'));
    }

    /**
     * Tests duplicated service insertion.
     */
    public function testDuplicatedServiceInsertion()
    {
        $registry = new Registry(new EventDispatcher());

        $this->setExpectedException('Bridge\Exception\KeyTakenInSetException');

        $service = new Service('test');
        $registry->addService($service);
        $registry->addService($service);
    }

    /**
     * Tests getService with non-existing service name.
     */
    public function testGetNonExistingService()
    {
        $this->setExpectedException('Bridge\Exception\KeyNotFoundInSetException');

        $registry = new Registry(new EventDispatcher());
        $registry->getService('non-existing');
    }
}
