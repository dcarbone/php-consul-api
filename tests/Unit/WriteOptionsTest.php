<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\PHPConsulAPI\WriteOptions;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class WriteOptionsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $wo = new WriteOptions();
        self::assertSame('', $wo->getNamespace());
        self::assertSame('', $wo->getDatacenter());
        self::assertSame('', $wo->getToken());
        self::assertSame(0, $wo->getRelayFactor());
        self::assertSame(0.0, $wo->getTimeout()->Seconds());
    }

    public function testConstructorWithValues(): void
    {
        $wo = new WriteOptions(
            Namespace: 'ns1',
            Datacenter: 'dc1',
            Token: 'tok-123',
            RelayFactor: 2,
            Timeout: '5s',
        );
        self::assertSame('ns1', $wo->getNamespace());
        self::assertSame('dc1', $wo->getDatacenter());
        self::assertSame('tok-123', $wo->getToken());
        self::assertSame(2, $wo->getRelayFactor());
        self::assertSame(5.0, $wo->getTimeout()->Seconds());
    }

    public function testFluentSetters(): void
    {
        $wo = new WriteOptions();
        $result = $wo
            ->setNamespace('ns')
            ->setDatacenter('dc')
            ->setToken('t')
            ->setRelayFactor(1)
            ->setTimeout('10s');

        self::assertSame($wo, $result);
        self::assertSame('ns', $wo->getNamespace());
        self::assertSame('dc', $wo->getDatacenter());
        self::assertSame('t', $wo->getToken());
        self::assertSame(1, $wo->getRelayFactor());
        self::assertSame(10.0, $wo->getTimeout()->Seconds());
    }

    public function testSetTimeoutWithNullResetsToZero(): void
    {
        $wo = new WriteOptions(Timeout: '30s');
        self::assertSame(30.0, $wo->getTimeout()->Seconds());

        $wo->setTimeout(null);
        self::assertSame(0.0, $wo->getTimeout()->Seconds());
    }

    public function testSetTimeoutWithIntNanoseconds(): void
    {
        $wo = new WriteOptions();
        $wo->setTimeout(2000000000); // 2 seconds in nanoseconds
        self::assertSame(2.0, $wo->getTimeout()->Seconds());
    }
}

