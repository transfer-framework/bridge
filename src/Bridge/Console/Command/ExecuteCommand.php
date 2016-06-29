<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Bridge\Console\Command;

use Bridge\Action\AbstractAction;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Command for executing actions.
 */
class ExecuteCommand extends BridgeCommand
{
    /**
     * @var EventDispatcherInterface Custom event dispatcher
     */
    protected $dispatcher;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('execute')
            ->setDescription('Execute an action')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('arguments', InputArgument::IS_ARRAY, 'Arguments');
    }

    /**
     * Sets custom event dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher Custom event dispatcher
     */
    public function setEventDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $action = $this->registry->get($input->getArgument('name'));

        if (!$action instanceof AbstractAction) {
            throw new \Exception(sprintf('Expected action, got %s', get_class($action)));
        }

        $response = $action->execute($input->getArgument('arguments'));

        $output->writeln(sprintf('Type: <info>%s</info>', gettype($response)));

        if (!is_callable(array($response, '__toString'))) {
            $response = serialize($response);
        }

        $output->writeln((string) $response);
    }
}
