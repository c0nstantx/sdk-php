<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace Tests\Core\Exception;
use RG\Exception\ReportNotFoundException;

/**
 * Description of ReportNotFoundExceptionTest
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ReportNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    const CUSTOM_MESSAGE = 'custom message';

    public function testException()
    {
        $exception = new ReportNotFoundException();
        $this->assertEquals(ReportNotFoundException::DEFAULT_MESSAGE, $exception->getMessage());

        $exception = new ReportNotFoundException(self::CUSTOM_MESSAGE);
        $this->assertEquals(self::CUSTOM_MESSAGE, $exception->getMessage());
    }
}