<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RG\Exception;

/**
 * Description of ConnectorNotFoundException.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ConnectorNotFoundException extends \RuntimeException
{
    const DEFAULT_MESSAGE = 'The defined connector does not exist';

    public function __construct($message = null, $code = 0)
    {
        if (null === $message) {
            $message = self::DEFAULT_MESSAGE;
        }
        parent::__construct($message, $code);
    }
}
