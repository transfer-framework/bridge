<?php

namespace Bridge\Action;

use Bridge\Element;

/**
 * Element action.
 */
abstract class AbstractAction
{
    /**
     * @var string Action name
     */
    private $name;

    /**
     * @var Element Parent element
     */
    protected $element;

    /**
     * @param string $name Action name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Sets parent element
     *
     * @param Element $element Parent element
     */
    public function setElement(Element $element)
    {
        $this->element = $element;
    }

    /**
     * Returns parent element
     *
     * @return Element
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Returns action name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Executes action
     *
     * @param  array $arguments Arguments passed to execution method
     * @return mixed Action response
     */
    abstract public function execute($arguments = array());
}
