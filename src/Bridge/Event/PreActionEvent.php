<?php

namespace Bridge\Event;

use Bridge\Action\AbstractAction;
use Symfony\Component\EventDispatcher\Event;

/**
 * Gets triggered before action execution.
 */
class PreActionEvent extends Event
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
     * @param AbstractAction $action
     * @param array          $arguments
     */
    public function __construct(AbstractAction $action, array $arguments)
    {
        $this->action = $action;
        $this->arguments = $arguments;
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
}
