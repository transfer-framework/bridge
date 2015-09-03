<?php

namespace Bridge\Tests;

use Bridge\Element;
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
     * Tests add- and getElement methods.
     */
    public function testAddGetElement()
    {
        $service = new Service('test-service');

        $element = new Element('test-element');
        $service->addElement($element);

        $this->assertNotNull($service->getElement('test-element'));
        $this->assertSame($element, $service->getElement('test-element'));
    }

    /**
     * Tests getElement method with non-existing element name.
     */
    public function testGetNonExistingElement()
    {
        $this->setExpectedException('Bridge\Exception\KeyNotFoundInSetException');

        $service = new Service('test-service');
        $service->getElement('non-existing');
    }
}
