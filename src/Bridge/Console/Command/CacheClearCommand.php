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
 * Command for clearing cache.
 */
class CacheClearCommand extends BridgeCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('cache:clear')
            ->setDescription('Clear cache for a cache pool')
            ->addArgument('name', InputArgument::REQUIRED, 'Pool name');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $name = $input->getArgument('name');

        $output->writeln(sprintf('Clearing cache for pool "%s"...', $name));

        if ($this->registry->getCachePool($name)->clear()) {
            $output->writeln('<info>Cache pool has been successfully cleared.</info>');
        } else {
            $output->writeln(sprintf('<error>An error occurred while clearing cache pool "%s"</error>', $name));
        }
    }
}
