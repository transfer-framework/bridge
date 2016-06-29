<?php

namespace Bridge;

use Bridge\Action\AbstractAction;
use Bridge\Event\BridgeEvents;
use Bridge\Event\PostActionEvent;
use Bridge\Event\PreActionEvent;
use Bridge\Exception\KeyNotFoundInSetException;

/**
 * Service group.
 */
class Group
{
    /**
     * @var array Action collection
     */
    private $actions = array();

    /**
     * @var string Group name
     */
    private $name;

    /**
     * @var Service
     */
    private $service;

    /**
     * @param string $name Group name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param Service $service
     */
    public function setService(Service $service)
    {
        $this->service = $service;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Returns group name.
     *
     * @return string Group name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Adds action.
     *
     * @param AbstractAction $action Group action
     *
     * @return $this
     */
    public function addAction(AbstractAction $action)
    {
        $action->setGroup($this);

        $this->actions[$action->getName()] = $action;

        return $this;
    }

    /**
     * @param string $name Action name
     *
     * @throws KeyNotFoundInSetException If action does not exist
     *
     * @return AbstractAction
     */
    public function getAction($name)
    {
        if (array_key_exists($name, $this->actions)) {
            return $this->actions[$name];
        }

        throw new KeyNotFoundInSetException($name, array_keys($this->actions), 'actions');
    }

    /**
     * Returns actions.
     *
     * @return AbstractAction[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param string $name      Action name
     * @param array  $arguments Arguments to be passed to action execution method
     *
     * @return mixed Action response
     */
    public function call($name, $arguments = array())
    {
        /** @var AbstractAction $action */
        $action = $this->getAction($name);

        if ($action instanceof RegistryAwareInterface) {
            $action->setRegistry($this->service->getRegistry());
        }

        $this->dispatchPreActionEvent($arguments, $action);

        $start = microtime(true);

        $response = $action->execute($arguments);

        $event = $this->dispatchPostActionEvent($arguments, $action, $response, microtime(true) - $start, $action->getExtraData());

        $response = $event->getResponse();

        return $response;
    }

    /**
     * @see Group::call()
     */
    public function __call($name, $arguments)
    {
        return $this->call($name, $arguments);
    }

    /**
     * @param array          $arguments Argument collection
     * @param AbstractAction $action    Action object
     */
    private function dispatchPreActionEvent(array $arguments, AbstractAction $action)
    {
        $this->service->getEventDispatcher()->dispatch(
            BridgeEvents::PRE_ACTION,
            new PreActionEvent($action, $arguments)
        );
    }

    /**
     * @param array          $arguments     Argument collection
     * @param AbstractAction $action        Action object
     * @param mixed          $response      Action response
     * @param float          $executionTime Total execution time
     * @param array          $extraData     Extra Data
     *
     * @return PostActionEvent
     */
    private function dispatchPostActionEvent(array $arguments, AbstractAction $action, $response, $executionTime, $extraData)
    {
        return $this->service->getEventDispatcher()->dispatch(
            BridgeEvents::POST_ACTION,
            new PostActionEvent($action, $arguments, $response, $executionTime, $extraData)
        );
    }
}
