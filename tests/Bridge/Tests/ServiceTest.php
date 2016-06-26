<?php

namespace Bridge\Tests;

use Bridge\Resource;
use Bridge\Service;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor.
     */
    public function testConstructor()
    {
        $service = new Service('test-service');

        $this->assertEquals('test-service', $service->getName());
    }

    /**
     * Tests add- and getResource methods.
     */
    public function testAddGetResource()
    {
        $service = new Service('test-service');

        $resource = new Resource('test-resource');
        $service->addResource($resource);

        $this->assertNotNull($service->getResource('test-resource'));
        $this->assertSame($resource, $service->getResource('test-resource'));
    }

    /**
     * Tests getResource method with non-existing resource name.
     */
    public function testGetNonExistingResource()
    {
        $this->setExpectedException('Bridge\Exception\KeyNotFoundInSetException');

        $service = new Service('test-service');
        $service->getResource('non-existing');
    }
}
