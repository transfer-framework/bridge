<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Bridge\Console\Command;

use Bridge\Action\AbstractAction;
use Bridge\RegistryAwareInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for executing actions.
 */
class ExecuteCommand extends BridgeCommand
{
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
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $action = $this->registry->get($input->getArgument('name'));

        if (!$action instanceof AbstractAction) {
            throw new \Exception(sprintf('Expected action, got %s', get_class($action)));
        }

        if ($action instanceof RegistryAwareInterface) {
            $action->setRegistry($this->registry);
        }

        $response = $action->execute($input->getArgument('arguments'));

        $output->writeln('RESPONSE METADATA');
        $output->writeln(sprintf('<info>Type</info>: %s', gettype($response)));

        $output->writeln('RESPONSE DATA');
        if (!is_callable(array($response, '__toString'))) {
            $response = serialize($response);
        }
        $output->writeln((string) $response);

        $output->writeln('EXTRA DATA');
        foreach ($action->getExtraData() as $key => $value) {
            $output->writeln(sprintf('<info>%s</info>: %s', $key, $value));
        }
    }
}
