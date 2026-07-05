<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteMatch;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteHTTPMatch;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceRouteMatchTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new ServiceRouteMatch();
        self::assertNull($m->getHTTP());
    }

    public function testConstructorWithParams(): void
    {
        $http = new ServiceRouteHTTPMatch(PathPrefix: '/api');
        $m = new ServiceRouteMatch(HTTP: $http);
        self::assertSame($http, $m->getHTTP());
    }

    public function testFluentSetters(): void
    {
        $http = new ServiceRouteHTTPMatch(PathPrefix: '/v1');
        $m = new ServiceRouteMatch();
        $result = $m->setHTTP($http);
        self::assertSame($m, $result);
        self::assertSame($http, $m->getHTTP());
    }

}
