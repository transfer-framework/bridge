<?php

namespace Bridge;

use Bridge\Exception\KeyNotFoundInSetException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Service.
 */
class Service
{
    /**
     * @var array Element collection
     */
    private $elements = array();

    /**
     * @var string Service name
     */
    private $name;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param string $name Service name
     */
    public function __construct($name)
    {
        $this->name = $name;

        $this->dispatcher = new EventDispatcher();
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
     * Adds an element.
     *
     * @param Element $element
     */
    public function addElement(Element $element)
    {
        $element->setService($this);

        $this->elements[$element->getName()] = $element;
    }

    /**
     * Returns an element in the service based on name.
     *
     * @param string $name Element name
     *
     * @throws KeyNotFoundInSetException
     *
     * @return Element|null Element object, if found.
     */
    public function getElement($name)
    {
        if (array_key_exists($name, $this->elements)) {
            return $this->elements[$name];
        }

        throw new KeyNotFoundInSetException($name, array_keys($this->elements), 'elements');
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
