<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\Exception;

/**
 * Description of ContentNotFoundException
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ContentNotFoundException extends \RuntimeException
{
    const DEFAULT_MESSAGE = 'No content was found';

    public function __construct($message)
    {
        $message = $message ?: self::DEFAULT_MESSAGE;

        return parent::__construct($message, 404);
    }
}