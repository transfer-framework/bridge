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
     * @var float
     */
    private $executionTime;

    /**
     * @param AbstractAction $action Action object
     * @param array          $arguments Argument collection
     * @param mixed          $response Response given by action after execution
     * @param float          $executionTime Execution time
     */
    public function __construct(AbstractAction $action, array $arguments, $response, $executionTime)
    {
        $this->action = $action;
        $this->arguments = $arguments;
        $this->response = $response;
        $this->executionTime = $executionTime;
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

    /**
     * @return float
     */
    public function getExecutionTime()
    {
        return $this->executionTime;
    }
}
