<?php

namespace Bridge\Exception;

class KeyTakenInSetException extends \Exception
{
    /**
     * Constructs an exception with information about taken key.
     *
     * @param string $key     Key
     * @param string $setName Set name
     */
    public function __construct($key, $setName = 'set')
    {
        parent::__construct(sprintf('Key "%s" is taken for %s, please choose another key.', $key, $setName));
    }
}
