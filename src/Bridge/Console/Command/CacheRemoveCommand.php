<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Bridge\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for clearing cache associated with an action.
 */
class CacheRemoveCommand extends BridgeCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('cache:remove')
            ->setDescription('Clear cache associated with an action')
            ->addArgument('pool_name', InputArgument::REQUIRED, 'Pool name')
            ->addArgument('action_name', InputArgument::REQUIRED, 'Action name')
            ->addArgument('arguments', InputArgument::IS_ARRAY, 'Action Arguments');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $poolName = $input->getArgument('pool_name');
        $pool = $this->registry->getCachePool($poolName);

        $actionName = $input->getArgument('action_name');
        $arguments = $input->getArgument('arguments');

        $id = $this->registry->generateCacheItemKey($actionName, $arguments);

        $output->writeln(sprintf('Removing cache item "%s" from pool "%s"...', $id, $poolName));

        if ($pool->deleteItem($id)) {
            $output->writeln('<info>Cache item has been successfully removed from the pool.</info>');
        } else {
            $output->writeln(sprintf('<error>An error occurred while removing a cache item from pool "%s"</error>', $poolName));
        }
    }
}
