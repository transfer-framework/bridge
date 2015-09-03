<?php

namespace Bridge\Tests\Action;

use Bridge\Action\CallbackAction;
use Bridge\Element;

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
     * Tests get- and setElement methods.
     */
    public function testGetSetElement()
    {
        $action = new CallbackAction('callback', function () { });

        $element = new Element('test');
        $action->setElement($element);

        $this->assertSame($element, $action->getElement());
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
