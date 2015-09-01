<?php

namespace Bridge\Tests\Action;

use Bridge\Action\HttpAction;

class HttpActionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $action = new HttpAction('test', 'GET', 'http://localhost/');

        $this->assertEquals('test', $action->getName());
    }

    public function testExecute()
    {
        $action = new HttpAction('test', 'GET', 'http://localhost/');

        $action->execute(array(
            'options' => array(
                'headers' => array(
                    'X-Test' => 'true',
                ),
            ),
        ));
    }
}
