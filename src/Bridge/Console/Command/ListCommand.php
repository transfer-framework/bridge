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
 * Command for executing actions.
 */
class ListCommand extends BridgeCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('list')
            ->setDescription('Lists registered components');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $services = $this->registry->getServices();

        $actions = array();

        foreach ($services as $service) {
            foreach ($service->getGroups() as $group) {
                foreach ($group->getActions() as $action) {
                    $actions[] = array(
                        $service->getName(),
                        $group->getName(),
                        $action->getName(),
                    );
                }
            }
        }

        $table = new Table($output);

        $table->setHeaders(array('Service', 'Group', 'Action'))
            ->setRows($actions);

        $table->render();
    }
}
