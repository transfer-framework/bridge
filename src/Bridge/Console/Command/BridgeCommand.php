<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Bridge\Console\Command;

use Bridge\Registry;
use Bridge\RegistryAwareInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base for bridge commands.
 */
abstract class BridgeCommand extends Command implements RegistryAwareInterface
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Registry $registry
     */
    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addOption('registry', 'r', InputOption::VALUE_REQUIRED, 'Registry');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getOption('registry');

        if ($file !== null) {
            if (!file_exists($file)) {
                throw new \InvalidArgumentException(sprintf('File "%s" could not be located.', $file));
            }

            $this->registry = require $file;
        }

        if ($this->registry === null) {
            throw new \RuntimeException('Missing registry');
        }
    }
}
