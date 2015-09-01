<?php

namespace Bridge\Exception;

class KeyNotFoundInSetException extends \Exception
{
    /**
     * Constructs an exception with information about missing key and available alternatives.
     *
     * @param string $key     Key
     * @param array  $set     Available elements in the set
     * @param string $setName Set name
     */
    public function __construct($key, $set = array(), $setName = 'set')
    {
        parent::__construct(sprintf(
            'Key "%s" not found among %s. Available choices: %s',
            $key,
            $setName,
            implode(', ', $set)
        ));
    }
}
