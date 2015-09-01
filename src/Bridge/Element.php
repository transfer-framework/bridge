<?php

namespace Bridge;

use Bridge\Action\AbstractAction;
use Bridge\Event\BridgeEvents;
use Bridge\Event\PostActionEvent;
use Bridge\Event\PreActionEvent;
use Bridge\Exception\KeyNotFoundInSetException;

/**
 * Service element.
 */
class Element
{
    /**
     * @var array Action collection
     */
    private $actions = array();

    /**
     * @var string Element name
     */
    private $name;

    /**
     * @var Service
     */
    private $service;

    /**
     * @param string $name Element name
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
     * Returns element name
     *
     * @return string Element name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Adds action
     *
     * @param  AbstractAction $action Element action
     * @return $this
     */
    public function addAction(AbstractAction $action)
    {
        $action->setElement($this);

        $this->actions[$action->getName()] = $action;

        return $this;
    }

    /**
     * @param  string                    $name Action name
     * @return AbstractAction
     * @throws KeyNotFoundInSetException If action does not exist
     */
    public function getAction($name)
    {
        if (array_key_exists($name, $this->actions)) {
            return $this->actions[$name];
        }

        throw new KeyNotFoundInSetException($name, array_keys($this->actions), 'actions');
    }

    /**
     * @param  string $name      Action name
     * @param  array  $arguments Arguments to be passed to action execution method
     * @return mixed  Action response
     */
    public function call($name, $arguments = array())
    {
        /** @var AbstractAction $action */
        $action = $this->getAction($name);

        $this->dispatchPreActionEvent($arguments, $action);

        $response = $action->execute($arguments);

        $this->dispatchPostActionEvent($arguments, $action, $response);

        return $response;
    }

    /**
     * @see Element::call()
     */
    public function __call($name, $arguments)
    {
        return $this->call($name, $arguments);
    }

    /**
     * @param array          $arguments
     * @param AbstractAction $action
     */
    private function dispatchPreActionEvent(array $arguments, AbstractAction $action)
    {
        $this->service->getEventDispatcher()->dispatch(
            BridgeEvents::PRE_ACTION,
            new PreActionEvent($action, $arguments)
        );
    }

    /**
     * @param array          $arguments
     * @param AbstractAction $action
     * @param mixed          $response
     */
    private function dispatchPostActionEvent(array $arguments, AbstractAction $action, $response)
    {
        $this->service->getEventDispatcher()->dispatch(
            BridgeEvents::POST_ACTION,
            new PostActionEvent($action, $arguments, $response)
        );
    }
}
