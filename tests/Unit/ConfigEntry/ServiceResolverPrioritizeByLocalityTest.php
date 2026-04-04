<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverPrioritizeByLocality;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceResolverPrioritizeByLocalityTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new ServiceResolverPrioritizeByLocality();
        self::assertSame('', $p->getMode());
    }

    public function testConstructorWithParams(): void
    {
        $p = new ServiceResolverPrioritizeByLocality(Mode: 'failover');
        self::assertSame('failover', $p->getMode());
    }

    public function testFluentSetters(): void
    {
        $p = new ServiceResolverPrioritizeByLocality();
        $result = $p->setMode('none');
        self::assertSame($p, $result);
        self::assertSame('none', $p->getMode());
    }

}
