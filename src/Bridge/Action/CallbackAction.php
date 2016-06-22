<?php

namespace Bridge\Action;

/**
 * Action where execution method is defined with a callable (callback, function, method reference, etc).
 */
class CallbackAction extends AbstractAction
{
    /**
     * @var callable Callable to be used in action execution
     */
    private $callable;

    /**
     * @param string   $name     Action response
     * @param callable $callable Callable to be used in action execution
     */
    public function __construct($name, callable $callable)
    {
        parent::__construct($name);

        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $arguments = array())
    {
        return call_user_func_array($this->callable, $arguments);
    }
}
