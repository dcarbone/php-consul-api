<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverPolicy;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceResolverFailoverPolicyTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new ServiceResolverFailoverPolicy();
        self::assertSame('', $p->getMode());
    }

    public function testConstructorWithParams(): void
    {
        $p = new ServiceResolverFailoverPolicy(Mode: 'sequential', Regions: ['us-west-1']);
        self::assertSame('sequential', $p->getMode());
    }

    public function testFluentSetters(): void
    {
        $p = new ServiceResolverFailoverPolicy();
        $result = $p->setMode('order-by-locality');
        self::assertSame($p, $result);
        self::assertSame('order-by-locality', $p->getMode());
    }

}
