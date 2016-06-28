<?php

namespace Bridge\Action;

use Transfer\Adapter\SingleBufferAdapter;
use Transfer\Adapter\Transaction\Request;
use Transfer\Procedure\ProcedureBuilder;
use Transfer\Processor\SequentialProcessor;

/**
 * Procedural action.
 */
class ProceduralAction extends AbstractAction
{
    /**
     * @var ProcedureBuilder
     */
    protected $builder;

    /**
     * @param string           $name    Action name
     * @param ProcedureBuilder $builder Procedure builder
     */
    public function __construct($name, ProcedureBuilder $builder = null)
    {
        parent::__construct($name);

        $this->builder = $builder;

        if ($this->builder === null) {
            $this->builder = new ProcedureBuilder();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $arguments = array())
    {
        $buffer = new SingleBufferAdapter();

        $this->builder->addTarget($buffer);

        $processor = new SequentialProcessor();
        $processor->addProcedure($this->builder->getProcedure());

        $processor->process();

        return $buffer->receive(new Request());
    }
}
