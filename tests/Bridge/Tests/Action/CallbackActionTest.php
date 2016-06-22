<?php

namespace Bridge\Tests\Action;

use Bridge\Action\CallbackAction;
use Bridge\Group;

class CallbackActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor.
     */
    public function testConstructor()
    {
        $action = new CallbackAction('callback', function () { });

        $this->assertEquals('callback', $action->getName());
    }

    /**
     * Tests get- and setGroup methods.
     */
    public function testGetSetGroup()
    {
        $action = new CallbackAction('callback', function () { });

        $group = new Group('test');
        $action->setGroup($group);

        $this->assertSame($group, $action->getGroup());
    }

    /**
     * Tests action execution.
     */
    public function testExecute()
    {
        $action = new CallbackAction('callback', function () { return 'test-response'; });

        $this->assertEquals('test-response', $action->execute());
    }
}
