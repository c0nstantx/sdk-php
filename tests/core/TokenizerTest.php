<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace tests\core;
use RG\Tokenizer;

/**
 * Description of TokenizerTest
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    const PATH = '../fixtures/reports';

    public function testExistingFile()
    {
        $file = __DIR__.'/'.self::PATH.'/report1/report1.php';
        $tokenizer = new Tokenizer($file);

        $this->assertTrue(is_array($tokenizer->getTokens()));
        $this->assertTrue($tokenizer->isExtended());
        $this->assertEquals('report1', $tokenizer->getClass());
        $this->assertEquals('RAM\BaseReport', $tokenizer->getBaseClass());
        $this->assertEquals(3, count($tokenizer->getMethods()));
    }

    public function testNonExistingFile()
    {
        $file = __DIR__.'/'.self::PATH.'/report1/report2.php';
        $tokenizer = new Tokenizer($file);

        $this->assertNull($tokenizer->getTokens());
    }
}