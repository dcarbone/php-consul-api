<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRoute;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteMatch;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceRouteDestination;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceRouteTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new ServiceRoute();
        self::assertNull($r->getMatch());
        self::assertNull($r->getDestination());
    }

    public function testConstructorWithParams(): void
    {
        $match = new ServiceRouteMatch();
        $dest = new ServiceRouteDestination(Service: 'web-v2');
        $r = new ServiceRoute(Match: $match, Destination: $dest);
        self::assertSame($match, $r->getMatch());
        self::assertSame($dest, $r->getDestination());
    }

    public function testFluentSetters(): void
    {
        $match = new ServiceRouteMatch();
        $dest = new ServiceRouteDestination(Service: 'api');
        $r = new ServiceRoute();
        $result = $r->setMatch($match)->setDestination($dest);
        self::assertSame($r, $result);
        self::assertSame($match, $r->getMatch());
        self::assertSame($dest, $r->getDestination());
    }

}
