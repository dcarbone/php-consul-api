<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\DestinationConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class DestinationConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new DestinationConfig();
        self::assertSame([], $c->getAddresses());
        self::assertSame(0, $c->getPort());
    }

    public function testConstructorWithParams(): void
    {
        $c = new DestinationConfig(Addresses: ['10.0.0.1', '10.0.0.2'], Port: 443);
        self::assertSame(['10.0.0.1', '10.0.0.2'], $c->getAddresses());
        self::assertSame(443, $c->getPort());
    }

    public function testFluentSetters(): void
    {
        $c = new DestinationConfig();
        $result = $c->setAddresses('1.2.3.4')->setPort(8080);
        self::assertSame($c, $result);
        self::assertSame(['1.2.3.4'], $c->getAddresses());
        self::assertSame(8080, $c->getPort());
    }

}
