<?php
/**
 * This file is part of Rocketgraph service
 * <http://www.rocketgraph.com>
 */

namespace Tests\Core;
use RG\Connection;

/**
 * Description of ConnectionTest
 *
 * @author K.Christofilos <kostas.christofilos@rocketgraph.com>
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    const ACCESS_TOKEN = 'access_token';
    const IDENTIFIER = 'identifier';
    const EXPIRES = 12345;
    const REFRESH_TOKEN = 'refresh_token';
    const SECRET = 'secret';
    const UID = 'uid';

    public function testCreate()
    {
        $connection = new Connection();
        $connection->setAccessToken(self::ACCESS_TOKEN);
        $connection->setIdentifier(self::IDENTIFIER);
        $connection->setExpires(self::EXPIRES);
        $connection->setRefreshToken(self::REFRESH_TOKEN);
        $connection->setSecret(self::SECRET);
        $connection->setUid(self::UID);

        $this->assertEquals(self::ACCESS_TOKEN, $connection->getAccessToken());
        $this->assertEquals(self::IDENTIFIER, $connection->getIdentifier());
        $this->assertEquals(self::EXPIRES, $connection->getExpires());
        $this->assertEquals(self::REFRESH_TOKEN, $connection->getRefreshToken());
        $this->assertEquals(self::SECRET, $connection->getSecret());
        $this->assertEquals(self::UID, $connection->getUid());
    }
}