<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\Area;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AreaTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $area = new Area();
        self::assertSame('', $area->getID());
        self::assertSame('', $area->ID);
        self::assertSame('', $area->getPeerDatacenter());
        self::assertSame('', $area->PeerDatacenter);
        self::assertSame([], $area->getRetryJoin());
        self::assertSame([], $area->RetryJoin);
        self::assertFalse($area->isUseTLS());
        self::assertFalse($area->UseTLS);
    }

    public function testConstructorWithValues(): void
    {
        $area = new Area(
            ID: 'area-1',
            PeerDatacenter: 'dc2',
            RetryJoin: ['10.0.0.1', '10.0.0.2'],
            UseTLS: true,
        );
        self::assertSame('area-1', $area->getID());
        self::assertSame('area-1', $area->ID);
        self::assertSame('dc2', $area->getPeerDatacenter());
        self::assertSame('dc2', $area->PeerDatacenter);
        self::assertSame(['10.0.0.1', '10.0.0.2'], $area->getRetryJoin());
        self::assertSame(['10.0.0.1', '10.0.0.2'], $area->RetryJoin);
        self::assertTrue($area->isUseTLS());
        self::assertTrue($area->UseTLS);
    }

    public function testFluentSetters(): void
    {
        $area = new Area();
        $result = $area
            ->setID('a')
            ->setPeerDatacenter('dc')
            ->setRetryJoin('1.2.3.4', '5.6.7.8')
            ->setUseTLS(true);

        self::assertSame($area, $result);
        self::assertSame('a', $area->getID());
        self::assertSame('a', $area->ID);
        self::assertSame('dc', $area->getPeerDatacenter());
        self::assertSame('dc', $area->PeerDatacenter);
        self::assertSame(['1.2.3.4', '5.6.7.8'], $area->getRetryJoin());
        self::assertSame(['1.2.3.4', '5.6.7.8'], $area->RetryJoin);
        self::assertTrue($area->isUseTLS());
        self::assertTrue($area->UseTLS);
    }

    public function testVariadicSetRetryJoinReplacesExisting(): void
    {
        $area = new Area(RetryJoin: ['old-addr']);
        $area->setRetryJoin('new-addr-1', 'new-addr-2');

        self::assertSame(['new-addr-1', 'new-addr-2'], $area->getRetryJoin());
    }

    public function testVariadicSetRetryJoinWithNoArgsClears(): void
    {
        $area = new Area(RetryJoin: ['addr']);
        $area->setRetryJoin();

        self::assertSame([], $area->getRetryJoin());
    }

    public function testJsonSerialize(): void
    {
        $area = new Area(
            ID: 'area-1',
            PeerDatacenter: 'dc2',
            RetryJoin: ['10.0.0.1'],
            UseTLS: true,
        );
        $out = $area->jsonSerialize();

        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('area-1', $out->ID);
        self::assertSame('dc2', $out->PeerDatacenter);
        self::assertSame(['10.0.0.1'], $out->RetryJoin);
        self::assertTrue($out->UseTLS);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'area-x';
        $decoded->PeerDatacenter = 'dc3';
        $decoded->RetryJoin = ['a', 'b'];
        $decoded->UseTLS = false;

        $area = Area::jsonUnserialize($decoded);

        self::assertSame('area-x', $area->getID());
        self::assertSame('dc3', $area->getPeerDatacenter());
        self::assertSame(['a', 'b'], $area->getRetryJoin());
        self::assertFalse($area->isUseTLS());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new Area(
            ID: 'area-rt',
            PeerDatacenter: 'dc-rt',
            RetryJoin: ['host-1', 'host-2'],
            UseTLS: true,
        );

        $restored = Area::jsonUnserialize($original->jsonSerialize());

        self::assertSame($original->getID(), $restored->getID());
        self::assertSame($original->getPeerDatacenter(), $restored->getPeerDatacenter());
        self::assertSame($original->getRetryJoin(), $restored->getRetryJoin());
        self::assertSame($original->isUseTLS(), $restored->isUseTLS());
    }
}

