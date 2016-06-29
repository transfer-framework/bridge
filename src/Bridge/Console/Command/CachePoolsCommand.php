<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Bridge\Console\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for list registered cache pools.
 */
class CachePoolsCommand extends BridgeCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('cache:pools')
            ->setDescription('List registered cache pools');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $pools = $this->registry->getCachePools();

        $rows = array();

        foreach ($pools as $name => $pool) {
            $rows[] = array($name, get_class($pool));
        }

        $table = new Table($output);

        $table->setHeaders(array('Name', 'Class'))
            ->setRows($rows);

        $table->render();
    }
}
