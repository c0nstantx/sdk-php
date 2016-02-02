<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace Tests\RAM\Exception;
use RAM\Exception\ConnectorNotFoundException;

/**
 * Description of ConnectorNotFoundExceptionTest
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ConnectorNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    const CUSTOM_MESSAGE = 'custom message';

    public function testException()
    {
        $exception = new ConnectorNotFoundException();
        $this->assertEquals(ConnectorNotFoundException::DEFAULT_MESSAGE, $exception->getMessage());

        $exception = new ConnectorNotFoundException(self::CUSTOM_MESSAGE);
        $this->assertEquals(self::CUSTOM_MESSAGE, $exception->getMessage());
    }
}