<?php

namespace Bridge;

use Bridge\Exception\KeyNotFoundInSetException;

/**
 * Service registry.
 */
class Registry
{
    /**
     * @var array Service collection
     */
    public $services = array();

    /**
     * Returns service object based on service name.
     *
     * @param string $name Service name
     *
     * @throws KeyNotFoundInSetException
     *
     * @return Service|null
     */
    public function getService($name)
    {
        if (array_key_exists($name, $this->services)) {
            return $this->services[$name];
        }

        throw new KeyNotFoundInSetException($name, array_keys($this->services), 'services');
    }

    /**
     * Adds service.
     *
     * @param Service $service
     *
     * @return $this
     */
    public function addService(Service $service)
    {
        $this->services[$service->getName()] = $service;

        return $this;
    }

    /**
     * Returns either a service, group or action based on component path.
     *
     * @param string $component
     *
     * @return object
     */
    public function get($component)
    {
        $parts = explode('.', $component);

        if (count($parts) == 1) {
            return $this->getService($parts[0]);
        } elseif (count($parts) == 2) {
            return $this->getService($parts[0])->getGroup($parts[1]);
        } elseif (count($parts) == 3) {
            return $this->getService($parts[0])->getGroup($parts[1])->getAction($parts[2]);
        }

        throw new \LogicException('Malformed component path. Please use a dot-notated path (e.g. service.group.action)');
    }
}
