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
     * @var AbstractAction Action object
     */
    private $action;

    /**
     * @var array Argument collection
     */
    private $arguments;

    /**
     * @var mixed Response given by action after execution
     */
    private $response;

    /**
     * @var float Execution time
     */
    private $executionTime;

    /**
     * @var array Extra data
     */
    private $extraData;

    /**
     * @param AbstractAction $action        Action object
     * @param array          $arguments     Argument collection
     * @param mixed          $response      Response given by action after execution
     * @param float          $executionTime Execution time
     * @param array          $extraData     Extra data
     */
    public function __construct(AbstractAction $action, array $arguments, $response, $executionTime, $extraData)
    {
        $this->action = $action;
        $this->arguments = $arguments;
        $this->response = $response;
        $this->executionTime = $executionTime;
        $this->extraData = $extraData;
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
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return float
     */
    public function getExecutionTime()
    {
        return $this->executionTime;
    }

    /**
     * @return mixed
     */
    public function getExtraData()
    {
        return $this->extraData;
    }
}
