<?php

namespace Bridge\Tests;

use Bridge\Group;
use Bridge\Event\BridgeEvents;
use Bridge\Service;
use Symfony\Component\EventDispatcher\EventDispatcher;

class GroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor.
     */
    public function testConstructor()
    {
        $group = new Group('test');

        $this->assertEquals('test', $group->getName());
    }

    /**
     * Tests call method.
     */
    public function testCall()
    {
        $service = new Service('test');
        $service->setEventDispatcher(new EventDispatcher());

        $group = new Group('test');
        $group->setService($service);

        $action = $this->getMockBuilder('Bridge\Action\AbstractAction')
            ->setConstructorArgs(array('testAction'))
            ->getMock();

        $action->method('execute')
            ->willReturn('test-action-response');

        $action->method('getName')
            ->willReturn('testAction');

        $group->addAction($action);

        $this->assertEquals('test-action-response', $group->call('testAction'));
        $this->assertEquals('test-action-response', $group->testAction());
    }

    public function testCallEvents()
    {
        $service = new Service('test');
        $service->setEventDispatcher(new EventDispatcher());

        $preActionBuffer = null;
        $postActionBuffer = null;

        $service
            ->addEventListener(BridgeEvents::PRE_ACTION, function () use (&$preActionBuffer) {
                $preActionBuffer = 'pre_action';
            })
            ->addEventListener(BridgeEvents::POST_ACTION, function () use (&$postActionBuffer) {
                $postActionBuffer = 'post_action';
            });

        $group = new Group('test');
        $group->setService($service);

        $action = $this->getMockBuilder('Bridge\Action\AbstractAction')
            ->setConstructorArgs(array('testAction'))
            ->getMock();

        $action->method('execute')
            ->willReturn('test-action-response');

        $action->method('getName')
            ->willReturn('testAction');

        $group->addAction($action);

        $group->call('testAction');

        $this->assertEquals('pre_action', $preActionBuffer);
        $this->assertEquals('post_action', $postActionBuffer);
    }

    /**
     * Tests call on non-existing action.
     */
    public function testCallOnNonExistingAction()
    {
        $this->setExpectedException('Bridge\Exception\KeyNotFoundInSetException');

        $group = new Group('test');
        $group->call('nonExisting');
        $group->nonExisting();
    }
}
