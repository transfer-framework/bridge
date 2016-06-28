<?php

namespace Bridge\Action;

use Bridge\Group;

/**
 * Abstract action.
 */
abstract class AbstractAction
{
    /**
     * @var string Action name
     */
    private $name;

    /**
     * @var Group Parent group
     */
    protected $group;

    /**
     * @var array Extra data
     */
    protected $extraData = array();

    /**
     * @param string $name Action name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Sets parent group.
     *
     * @param Group $group Parent group
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Returns parent group.
     *
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
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
     * Returns extra data.
     *
     * @return array Extra data
     */
    public function getExtraData()
    {
        return $this->extraData;
    }

    /**
     * Executes action.
     *
     * @param array $arguments Arguments passed to execution method
     *
     * @return mixed Action response
     */
    abstract public function execute(array $arguments = array());
}
