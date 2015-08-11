<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace Tests\Core\Exception;
use RG\Exception\ExtensionNotLoadedException;

/**
 * Description of ExtensionNotLoadedException
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ExtensionNotLoadedExceptionTest extends \PHPUnit_Framework_TestCase
{
    const EXTENSION = 'extension';

    public function testException()
    {
        $exception = new ExtensionNotLoadedException();
        $this->assertEquals("'unknown' twig extension was not loaded in render engine", $exception->getMessage());

        $exception = new ExtensionNotLoadedException(self::EXTENSION);
        $this->assertEquals("'".self::EXTENSION."' twig extension was not loaded in render engine", $exception->getMessage());
    }
}