<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverSubset;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceResolverSubsetTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new ServiceResolverSubset();
        self::assertSame('', $s->getFilter());
        self::assertFalse($s->isOnlyPassing());
    }

    public function testConstructorWithParams(): void
    {
        $s = new ServiceResolverSubset(Filter: 'Service.Meta.version == v1', OnlyPassing: true);
        self::assertSame('Service.Meta.version == v1', $s->getFilter());
        self::assertTrue($s->isOnlyPassing());
    }

    public function testFluentSetters(): void
    {
        $s = new ServiceResolverSubset();
        $result = $s->setFilter('filter')
            ->setOnlyPassing(true);
        self::assertSame($s, $result);
        self::assertSame('filter', $s->getFilter());
        self::assertTrue($s->isOnlyPassing());
    }

}
