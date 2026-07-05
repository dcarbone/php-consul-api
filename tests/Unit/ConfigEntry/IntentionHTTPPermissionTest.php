<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\IntentionHTTPPermission;
use DCarbone\PHPConsulAPI\ConfigEntry\IntentionHTTPHeaderPermission;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class IntentionHTTPPermissionTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new IntentionHTTPPermission();
        self::assertSame('', $p->getPathExact());
        self::assertSame('', $p->getPathPrefix());
        self::assertSame('', $p->getPathRegex());
        self::assertSame([], $p->getHeader());
        self::assertSame([], $p->getMethods());
    }

    public function testConstructorWithParams(): void
    {
        $h = new IntentionHTTPHeaderPermission(Name: 'x-header');
        $p = new IntentionHTTPPermission(
            PathExact: '/api/v1',
            PathPrefix: '/api',
            PathRegex: '/api/.*',
            Header: [$h],
            Methods: ['GET', 'POST'],
        );
        self::assertSame('/api/v1', $p->getPathExact());
        self::assertSame('/api', $p->getPathPrefix());
        self::assertSame('/api/.*', $p->getPathRegex());
        self::assertCount(1, $p->getHeader());
        self::assertSame(['GET', 'POST'], $p->getMethods());
    }

    public function testFluentSetters(): void
    {
        $p = new IntentionHTTPPermission();
        $result = $p->setPathExact('/exact')
            ->setPathPrefix('/prefix')
            ->setPathRegex('/re.*')
            ->setMethods('GET');
        self::assertSame($p, $result);
        self::assertSame('/exact', $p->getPathExact());
        self::assertSame('/prefix', $p->getPathPrefix());
        self::assertSame('/re.*', $p->getPathRegex());
        self::assertSame(['GET'], $p->getMethods());
    }

}
