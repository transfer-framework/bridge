<?php

namespace Bridge\Action;

use Bridge\Resource;

/**
 * Resource action.
 */
abstract class AbstractAction
{
    /**
     * @var string Action name
     */
    private $name;

    /**
     * @var Resource Parent resource
     */
    protected $resource;

    /**
     * @param string $name Action name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Sets parent resource.
     *
     * @param Resource $resource Parent resource
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Returns parent resource.
     *
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Returns action name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Executes action.
     *
     * @param array $arguments Arguments passed to execution method
     *
     * @return mixed Action response
     */
    abstract public function execute($arguments = array());
}
