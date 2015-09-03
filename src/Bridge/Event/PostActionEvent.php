<?php

namespace Bridge\Event;

use Bridge\Action\AbstractAction;
use Symfony\Component\EventDispatcher\Event;

/**
 * Gets triggered after action execution.
 */
class PostActionEvent extends Event
{
    /**
     * @var AbstractAction
     */
    private $action;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var mixed
     */
    private $response;

    /**
     * @param AbstractAction $action    Action object
     * @param array          $arguments Argument collection
     * @param mixed          $response  Response given by action after execution
     */
    public function __construct(AbstractAction $action, array $arguments, $response)
    {
        $this->action = $action;
        $this->arguments = $arguments;
        $this->response = $response;
    }

    /**
     * @return AbstractAction
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
}
