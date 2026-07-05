<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\CookieConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class CookieConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new CookieConfig();
        self::assertFalse($c->getSession());
        self::assertSame(0, $c->getTTL()->Nanoseconds());
        self::assertSame('', $c->getPath());
    }

    public function testConstructorWithParams(): void
    {
        $c = new CookieConfig(Session: true, TTL: '5m', Path: '/');
        self::assertTrue($c->getSession());
        self::assertSame('/', $c->getPath());
    }

    public function testFluentSetters(): void
    {
        $c = new CookieConfig();
        $result = $c->setSession(true)->setPath('/test');
        self::assertSame($c, $result);
        self::assertTrue($c->getSession());
        self::assertSame('/test', $c->getPath());
    }

}
