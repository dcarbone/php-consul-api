<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyConfig;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class TransparentProxyConfigTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new TransparentProxyConfig();
        self::assertSame(0, $c->getOutboundListenerPort());
        self::assertFalse($c->isDialedDirectly());
    }

    public function testConstructorWithParams(): void
    {
        $c = new TransparentProxyConfig(OutboundListenerPort: 15001, DialedDirectly: true);
        self::assertSame(15001, $c->getOutboundListenerPort());
        self::assertTrue($c->isDialedDirectly());
    }

    public function testFluentSetters(): void
    {
        $c = new TransparentProxyConfig();
        $result = $c->setOutboundListenerPort(15001)->setDialedDirectly(true);
        self::assertSame($c, $result);
        self::assertSame(15001, $c->getOutboundListenerPort());
        self::assertTrue($c->isDialedDirectly());
    }

}
