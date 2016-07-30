<?php

namespace Bridge;

use Bridge\Exception\KeyNotFoundInSetException;
use Bridge\Exception\KeyTakenInSetException;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Bridge registry.
 */
class Registry
{
    /**
     * @var Service[] Service collection
     */
    public $services = array();

    /**
     * @var CacheItemPoolInterface[] Cache pools
     */
    public $cachePools = array();

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
     *
     * @throws KeyTakenInSetException
     */
    public function addService(Service $service)
    {
        if (array_key_exists($service->getName(), $this->services)) {
            throw new KeyTakenInSetException($service->getName(), 'services');
        }

        $this->services[$service->getName()] = $service;

        return $this;
    }

    /**
     * Returns registered services.
     *
     * @return Service[]
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Returns a cache pool.
     *
     * @param string $name Cache pool name
     *
     * @throws KeyNotFoundInSetException
     *
     * @return CacheItemPoolInterface
     */
    public function getCachePool($name)
    {
        if (array_key_exists($name, $this->cachePools)) {
            return $this->cachePools[$name];
        }

        throw new KeyNotFoundInSetException($name, array_keys($this->cachePools), 'cache pools');
    }

    /**
     * @param CacheItemPoolInterface $cachePool
     */
    public function addCachePool($name, CacheItemPoolInterface $cachePool)
    {
        $this->cachePools[$name] = $cachePool;
    }

    /**
     * Returns cache pools.
     *
     * @return CacheItemPoolInterface[]
     */
    public function getCachePools()
    {
        return $this->cachePools;
    }

    /**
     * Generate cache ID.
     *
     * @param string $action
     * @param array  $arguments
     *
     * @return string
     */
    public function generateCacheItemKey($action, array $arguments)
    {
        return sprintf('%s.%s', $action, md5(serialize($arguments)));
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
