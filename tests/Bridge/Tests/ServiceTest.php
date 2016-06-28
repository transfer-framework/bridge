<?php

namespace Bridge\Tests;

use Bridge\Group;
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
     * Tests add- and getGroup methods.
     */
    public function testAddGetGroup()
    {
        $service = new Service('test-service');

        $group = new Group('test-group');
        $service->addGroup($group);

        $this->assertNotNull($service->getGroup('test-group'));
        $this->assertSame($group, $service->getGroup('test-group'));
    }

    /**
     * Tests getGroup method with non-existing group name.
     */
    public function testGetNonExistingGroup()
    {
        $this->setExpectedException('Bridge\Exception\KeyNotFoundInSetException');

        $service = new Service('test-service');
        $service->getGroup('non-existing');
    }
}
