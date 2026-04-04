<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchQueryParam;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceRouteHTTPMatchQueryParamTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $q = new ServiceRouteHTTPMatchQueryParam();
        self::assertSame('', $q->getName());
        self::assertFalse($q->isPresent());
        self::assertSame('', $q->getExact());
        self::assertSame('', $q->getRegex());
    }

    public function testConstructorWithParams(): void
    {
        $q = new ServiceRouteHTTPMatchQueryParam(
            Name: 'env',
            Present: true,
            Exact: 'prod',
            Regex: '.*',
        );
        self::assertSame('env', $q->getName());
        self::assertTrue($q->isPresent());
        self::assertSame('prod', $q->getExact());
        self::assertSame('.*', $q->getRegex());
    }

    public function testFluentSetters(): void
    {
        $q = new ServiceRouteHTTPMatchQueryParam();
        $result = $q->setName('key')
            ->setPresent(true)
            ->setExact('val')
            ->setRegex('r');
        self::assertSame($q, $result);
        self::assertSame('key', $q->getName());
        self::assertTrue($q->isPresent());
        self::assertSame('val', $q->getExact());
        self::assertSame('r', $q->getRegex());
    }

}
