<?php

namespace Bridge\Tests;

use Bridge\Resource;
use Bridge\Event\BridgeEvents;
use Bridge\Service;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor.
     */
    public function testConstructor()
    {
        $resource = new Resource('test');

        $this->assertEquals('test', $resource->getName());
    }

    /**
     * Tests call method.
     */
    public function testCall()
    {
        $resource = new Resource('test');
        $resource->setService(new Service('test'));

        $action = $this->getMockBuilder('Bridge\Action\AbstractAction')
            ->setConstructorArgs(array('testAction'))
            ->getMock();

        $action->method('execute')
            ->willReturn('test-action-response');

        $action->method('getName')
            ->willReturn('testAction');

        $resource->addAction($action);

        $this->assertEquals('test-action-response', $resource->call('testAction'));
        $this->assertEquals('test-action-response', $resource->testAction());
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
            });

        $resource = new Resource('test');
        $resource->setService($service);

        $action = $this->getMockBuilder('Bridge\Action\AbstractAction')
            ->setConstructorArgs(array('testAction'))
            ->getMock();

        $action->method('execute')
            ->willReturn('test-action-response');

        $action->method('getName')
            ->willReturn('testAction');

        $resource->addAction($action);

        $resource->call('testAction');

        $this->assertEquals('pre_action', $preActionBuffer);
        $this->assertEquals('post_action', $postActionBuffer);
    }

    /**
     * Tests call on non-existing action.
     */
    public function testCallOnNonExistingAction()
    {
        $this->setExpectedException('Bridge\Exception\KeyNotFoundInSetException');

        $resource = new Resource('test');
        $resource->call('nonExisting');
        $resource->nonExisting();
    }
}
