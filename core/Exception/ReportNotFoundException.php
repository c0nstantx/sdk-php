<?php

/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>.
 */

namespace RG\Exception;

/**
 * Description of ReportNotFoundException.
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ReportNotFoundException extends \RuntimeException
{
    const DEFAULT_MESSAGE = 'No valid report found';

    public function __construct($message = null, $code = 0)
    {
        if (null === $message) {
            $message = self::DEFAULT_MESSAGE;
        }
        parent::__construct($message, $code);
    }
}
