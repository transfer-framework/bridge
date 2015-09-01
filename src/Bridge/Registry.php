<?php

namespace Bridge;

use Bridge\Exception\KeyNotFoundInSetException;

/**
 * Service registry.
 */
class Registry
{
    /**
     * @var array Manager collection
     */
    public $managers = array();

    /**
     * Returns service object based on service name
     *
     * @param $name Service name
     * @return Service|null
     * @throws KeyNotFoundInSetException
     */
    public function getService($name)
    {
        if (array_key_exists($name, $this->managers)) {
            return $this->managers[$name];
        }

        throw new KeyNotFoundInSetException($name, array_keys($this->managers), 'services');
    }

    /**
     * Adds service
     *
     * @param  Service $service
     * @return $this
     */
    public function addService(Service $service)
    {
        $this->managers[$service->getName()] = $service;

        return $this;
    }
}
