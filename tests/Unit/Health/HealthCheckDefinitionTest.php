<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\PHPConsulAPI\Health\HealthCheckDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HealthCheckDefinitionTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $d = new HealthCheckDefinition();
        self::assertSame('', $d->getHTTP());
        self::assertSame('', $d->getMethod());
        self::assertSame('', $d->getBody());
        self::assertFalse($d->isTLSSkipVerify());
        self::assertSame('', $d->getTCP());
        self::assertFalse($d->isTCPUseTLS());
        self::assertSame('', $d->getUDP());
        self::assertSame('', $d->getGRPC());
        self::assertSame('', $d->getOSService());
        self::assertFalse($d->isGRPCUseTLS());
    }

    public function testConstructorWithValues(): void
    {
        $d = new HealthCheckDefinition(
            HTTP: 'http://localhost:8080/health',
            Method: 'GET',
            TLSSkipVerify: true,
            IntervalDuration: '10s',
            TimeoutDuration: '5s',
        );
        self::assertSame('http://localhost:8080/health', $d->getHTTP());
        self::assertSame('GET', $d->getMethod());
        self::assertTrue($d->isTLSSkipVerify());
    }

    public function testFluentSetters(): void
    {
        $d = new HealthCheckDefinition();
        $result = $d
            ->setHTTP('http://test')
            ->setMethod('POST')
            ->setBody('body')
            ->setTLSSkipVerify(true)
            ->setTCP('localhost:8080')
            ->setTCPUseTLS(true)
            ->setUDP('localhost:53')
            ->setGRPC('localhost:50051')
            ->setOSService('svc')
            ->setGRPCUseTLS(true);
        self::assertSame($d, $result);
    }

    public function testJsonSerialize(): void
    {
        $d = new HealthCheckDefinition(HTTP: 'http://test', Method: 'GET');
        $out = $d->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('http://test', $out->HTTP);
        self::assertSame('GET', $out->Method);
    }
}

