<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatch;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchHeader;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatchQueryParam;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceRouteHTTPMatchTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $h = new ServiceRouteHTTPMatch();
        self::assertSame('', $h->getPathExact());
        self::assertSame('', $h->getPathPrefix());
        self::assertSame('', $h->getPathRegex());
        self::assertSame([], $h->getHeader());
        self::assertSame([], $h->getQueryParam());
        self::assertSame([], $h->getMethods());
    }

    public function testConstructorWithParams(): void
    {
        $header = new ServiceRouteHTTPMatchHeader(Name: 'x-version', Exact: 'v2');
        $qp = new ServiceRouteHTTPMatchQueryParam(Name: 'env', Exact: 'prod');
        $h = new ServiceRouteHTTPMatch(
            PathPrefix: '/api',
            Header: [$header],
            QueryParam: [$qp],
            Methods: ['GET', 'POST'],
        );
        self::assertSame('/api', $h->getPathPrefix());
        self::assertCount(1, $h->getHeader());
        self::assertCount(1, $h->getQueryParam());
        self::assertSame(['GET', 'POST'], $h->getMethods());
    }

    public function testFluentSetters(): void
    {
        $h = new ServiceRouteHTTPMatch();
        $result = $h->setPathExact('/exact')
            ->setPathPrefix('/prefix')
            ->setPathRegex('/re.*')
            ->setMethods('GET');
        self::assertSame($h, $result);
        self::assertSame('/exact', $h->getPathExact());
        self::assertSame('/prefix', $h->getPathPrefix());
        self::assertSame('/re.*', $h->getPathRegex());
        self::assertSame(['GET'], $h->getMethods());
    }

}
