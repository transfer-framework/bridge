<?php

namespace Bridge\Tests;

use Bridge\Element;
use Bridge\Event\BridgeEvents;
use Bridge\Service;

class ElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor
     */
    public function testConstructor()
    {
        $element = new Element('test');

        $this->assertEquals('test', $element->getName());
    }

    /**
     * Tests call method
     */
    public function testCall()
    {
        $element = new Element('test');
        $element->setService(new Service('test'));

        $action = $this->getMockBuilder('Bridge\Action\AbstractAction')
            ->setConstructorArgs(array('testAction'))
            ->getMock();

        $action->method('execute')
            ->willReturn('test-action-response');

        $action->method('getName')
            ->willReturn('testAction');

        $element->addAction($action);

        $this->assertEquals('test-action-response', $element->call('testAction'));
        $this->assertEquals('test-action-response', $element->testAction());
    }

    public function testCallEvents()
    {
        $service = new Service('test');

        $preActionBuffer = null;
        $postActionBuffer = null;

        $service
            ->addEventListener(BridgeEvents::PRE_ACTION, function () use (&$preActionBuffer) {
                $preActionBuffer = 'pre_action';
            })
            ->addEventListener(BridgeEvents::POST_ACTION, function () use (&$postActionBuffer) {
                $postActionBuffer = 'post_action';
            })
        ;

        $element = new Element('test');
        $element->setService($service);

        $action = $this->getMockBuilder('Bridge\Action\AbstractAction')
            ->setConstructorArgs(array('testAction'))
            ->getMock();

        $action->method('execute')
            ->willReturn('test-action-response');

        $action->method('getName')
            ->willReturn('testAction');

        $element->addAction($action);

        $element->call('testAction');

        $this->assertEquals('pre_action', $preActionBuffer);
        $this->assertEquals('post_action', $postActionBuffer);
    }

    /**
     * Tests call on non-existing action
     */
    public function testCallOnNonExistingAction()
    {
        $this->setExpectedException('Bridge\Exception\KeyNotFoundInSetException');

        $element = new Element('test');
        $element->call('nonExisting');
        $element->nonExisting();
    }
}
