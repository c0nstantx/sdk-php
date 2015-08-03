<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace RG\Exception;

/**
 * Description of ExtensionNotLoaded
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ExtensionNotLoadedException extends \RuntimeException
{
    const DEFAULT_MESSAGE = "'%s' twig extension was not loaded in render engine";

    public function __construct($extension = 'unknown')
    {
        parent::__construct(sprintf(self::DEFAULT_MESSAGE, $extension));
    }
}