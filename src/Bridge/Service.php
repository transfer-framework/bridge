<?php

namespace Bridge;

use Bridge\Exception\KeyNotFoundInSetException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Service.
 */
class Service implements RegistryAwareInterface
{
    /**
     * @var array Group collection
     */
    private $groups = array();

    /**
     * @var string Service name
     */
    private $name;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param string $name Service name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param Registry $registry
     */
    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return Registry
     */
    public function getRegistry()
    {
        return $this->registry;
    }


    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function setEventDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Adds an event listener.
     *
     * @param string   $name
     * @param callable $listener
     *
     * @return $this
     */
    public function addEventListener($name, $listener)
    {
        $this->dispatcher->addListener($name, $listener);

        return $this;
    }

    /**
     * Adds an event subscriber.
     *
     * @param EventSubscriberInterface $subscriber
     *
     * @return $this
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->addSubscriber($subscriber);

        return $this;
    }

    /**
     * Adds a group.
     *
     * @param Group $group
     */
    public function addGroup(Group $group)
    {
        $group->setService($this);

        $this->groups[$group->getName()] = $group;
    }

    /**
     * Returns a group in the service based on name.
     *
     * @param string $name Group name
     *
     * @throws KeyNotFoundInSetException
     *
     * @return object Group object, if found.
     */
    public function getGroup($name)
    {
        if (array_key_exists($name, $this->groups)) {
            return $this->groups[$name];
        }

        throw new KeyNotFoundInSetException($name, array_keys($this->groups), 'groups');
    }

    /**
     * Returns service name.
     *
     * @return string Service name
     */
    public function getName()
    {
        return $this->name;
    }
}
