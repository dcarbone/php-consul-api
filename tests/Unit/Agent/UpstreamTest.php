<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\Upstream;
use DCarbone\PHPConsulAPI\Agent\UpstreamDestType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UpstreamTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $u = new Upstream();
        self::assertSame(UpstreamDestType::UNDEFINED, $u->getDestinationType());
        self::assertSame('', $u->getDestinationPartition());
        self::assertSame('', $u->getDestinationNamespace());
        self::assertSame('', $u->getDestinationPeer());
        self::assertSame('', $u->getDestinationName());
        self::assertSame('', $u->getDatacenter());
        self::assertSame('', $u->getLocalBindAddress());
        self::assertSame(0, $u->getLocalBindPort());
        self::assertFalse($u->isCentrallyConfigured());
    }

    public function testConstructorWithParams(): void
    {
        $u = new Upstream(
            DestinationType: UpstreamDestType::Service,
            DestinationName: 'db',
            LocalBindAddress: '127.0.0.1',
            LocalBindPort: 5432,
        );
        self::assertSame(UpstreamDestType::Service, $u->getDestinationType());
        self::assertSame('db', $u->getDestinationName());
        self::assertSame(5432, $u->getLocalBindPort());
    }

    public function testConstructorWithStringDestType(): void
    {
        $u = new Upstream(DestinationType: 'service', DestinationName: 'db');
        self::assertSame(UpstreamDestType::Service, $u->getDestinationType());
    }

    public function testFluentSetters(): void
    {
        $u = new Upstream();
        $result = $u->setDestinationType(UpstreamDestType::PreparedQuery)
            ->setDestinationName('query')
            ->setLocalBindPort(9999);
        self::assertSame($u, $result);
        self::assertSame(UpstreamDestType::PreparedQuery, $u->getDestinationType());
    }

    public function testJsonSerialize(): void
    {
        $u = new Upstream(DestinationType: UpstreamDestType::Service, DestinationName: 'db', LocalBindPort: 5432);
        $out = $u->jsonSerialize();
        self::assertSame('db', $out->DestinationName);
        self::assertSame(5432, $out->LocalBindPort);
    }
}

