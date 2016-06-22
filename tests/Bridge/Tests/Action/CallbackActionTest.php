<?php

namespace Bridge\Tests\Action;

use Bridge\Action\CallbackAction;
use Bridge\Resource;

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
     * Tests get- and setResource methods.
     */
    public function testGetSetResource()
    {
        $action = new CallbackAction('callback', function () { });

        $resource = new Resource('test');
        $action->setResource($resource);

        $this->assertSame($resource, $action->getResource());
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
