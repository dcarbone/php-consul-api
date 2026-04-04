<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchHeader;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceRouteHTTPMatchHeaderTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $h = new ServiceRouteHTTPMatchHeader();
        self::assertSame('', $h->getName());
        self::assertFalse($h->isPresent());
        self::assertSame('', $h->getExact());
        self::assertSame('', $h->getPrefix());
        self::assertSame('', $h->getSuffix());
        self::assertSame('', $h->getRegex());
    }

    public function testConstructorWithParams(): void
    {
        $h = new ServiceRouteHTTPMatchHeader(
            Name: 'x-version',
            Present: true,
            Exact: 'v2',
            Prefix: 'pre',
            Suffix: 'suf',
            Regex: '.*',
            Invert: true,
        );
        self::assertSame('x-version', $h->getName());
        self::assertTrue($h->isPresent());
        self::assertSame('v2', $h->getExact());
        self::assertSame('pre', $h->getPrefix());
        self::assertSame('suf', $h->getSuffix());
        self::assertSame('.*', $h->getRegex());
    }

    public function testFluentSetters(): void
    {
        $h = new ServiceRouteHTTPMatchHeader();
        $result = $h->setName('x-test')
            ->setPresent(true)
            ->setExact('val')
            ->setPrefix('p')
            ->setSuffix('s')
            ->setRegex('r');
        self::assertSame($h, $result);
        self::assertSame('x-test', $h->getName());
        self::assertTrue($h->isPresent());
        self::assertSame('val', $h->getExact());
    }

}
