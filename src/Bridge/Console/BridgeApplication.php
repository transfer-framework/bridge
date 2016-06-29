<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Bridge\Console;

use Symfony\Component\Console\Application;
use Bridge\Console\Command as Commands;

/**
 * Bridge application.
 */
class BridgeApplication extends Application
{
    public function __construct()
    {
        parent::__construct('Bridge Command Line Interface', 'v1.0');

        $this->addCommands(array(
            new Commands\ExecuteCommand(),
            new Commands\ListCommand(),
        ));
    }
}
