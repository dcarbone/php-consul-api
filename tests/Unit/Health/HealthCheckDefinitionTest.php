<?php

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\Health\HealthCheckDefinition;
use DCarbone\PHPConsulAPI\PHPLib\Values;
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
        self::assertSame('', $d->HTTP);
        self::assertInstanceOf(Values::class, $d->getHeader());
        self::assertInstanceOf(Values::class, $d->Header);
        self::assertSame(0, $d->Header->count());
        self::assertSame('', $d->getMethod());
        self::assertSame('', $d->Method);
        self::assertSame('', $d->getBody());
        self::assertSame('', $d->Body);
        self::assertFalse($d->isTLSSkipVerify());
        self::assertFalse($d->TLSSkipVerify);
        self::assertSame('', $d->getTCP());
        self::assertSame('', $d->TCP);
        self::assertFalse($d->isTCPUseTLS());
        self::assertFalse($d->TCPUseTLS);
        self::assertSame('', $d->getUDP());
        self::assertSame('', $d->UDP);
        self::assertSame('', $d->getGRPC());
        self::assertSame('', $d->GRPC);
        self::assertSame('', $d->getOSService());
        self::assertSame('', $d->OSService);
        self::assertFalse($d->isGRPCUseTLS());
        self::assertFalse($d->GRPCUseTLS);
        self::assertInstanceOf(Time\Duration::class, $d->getIntervalDuration());
        self::assertInstanceOf(Time\Duration::class, $d->IntervalDuration);
        self::assertInstanceOf(Time\Duration::class, $d->getTimeoutDuration());
        self::assertInstanceOf(Time\Duration::class, $d->TimeoutDuration);
        self::assertInstanceOf(Time\Duration::class, $d->getDeregisterCriticalServiceAfterDuration());
        self::assertInstanceOf(Time\Duration::class, $d->DeregisterCriticalServiceAfterDuration);
    }

    public function testConstructorWithValues(): void
    {
        $d = new HealthCheckDefinition(
            HTTP: 'http://localhost:8080/health',
            Method: 'GET',
            Body: '{}',
            TLSSkipVerify: true,
            TCP: 'localhost:8080',
            TCPUseTLS: true,
            UDP: 'localhost:53',
            GRPC: 'localhost:50051',
            OSService: 'my-svc',
            GRPCUseTLS: true,
            IntervalDuration: '10s',
            TimeoutDuration: '5s',
            DeregisterCriticalServiceAfterDuration: '30s',
        );
        self::assertSame('http://localhost:8080/health', $d->getHTTP());
        self::assertSame('http://localhost:8080/health', $d->HTTP);
        self::assertSame('GET', $d->getMethod());
        self::assertSame('GET', $d->Method);
        self::assertSame('{}', $d->getBody());
        self::assertSame('{}', $d->Body);
        self::assertTrue($d->isTLSSkipVerify());
        self::assertTrue($d->TLSSkipVerify);
        self::assertSame('localhost:8080', $d->getTCP());
        self::assertSame('localhost:8080', $d->TCP);
        self::assertTrue($d->isTCPUseTLS());
        self::assertTrue($d->TCPUseTLS);
        self::assertSame('localhost:53', $d->getUDP());
        self::assertSame('localhost:53', $d->UDP);
        self::assertSame('localhost:50051', $d->getGRPC());
        self::assertSame('localhost:50051', $d->GRPC);
        self::assertSame('my-svc', $d->getOSService());
        self::assertSame('my-svc', $d->OSService);
        self::assertTrue($d->isGRPCUseTLS());
        self::assertTrue($d->GRPCUseTLS);
    }

    public function testSettersWithDirectFieldAccess(): void
    {
        $d = new HealthCheckDefinition();

        $d->setHTTP('http://test');
        self::assertSame('http://test', $d->getHTTP());
        self::assertSame('http://test', $d->HTTP);

        $d->setMethod('POST');
        self::assertSame('POST', $d->getMethod());
        self::assertSame('POST', $d->Method);

        $d->setBody('body');
        self::assertSame('body', $d->getBody());
        self::assertSame('body', $d->Body);

        $d->setTLSSkipVerify(true);
        self::assertTrue($d->isTLSSkipVerify());
        self::assertTrue($d->TLSSkipVerify);

        $d->setTCP('localhost:8080');
        self::assertSame('localhost:8080', $d->getTCP());
        self::assertSame('localhost:8080', $d->TCP);

        $d->setTCPUseTLS(true);
        self::assertTrue($d->isTCPUseTLS());
        self::assertTrue($d->TCPUseTLS);

        $d->setUDP('localhost:53');
        self::assertSame('localhost:53', $d->getUDP());
        self::assertSame('localhost:53', $d->UDP);

        $d->setGRPC('localhost:50051');
        self::assertSame('localhost:50051', $d->getGRPC());
        self::assertSame('localhost:50051', $d->GRPC);

        $d->setOSService('svc');
        self::assertSame('svc', $d->getOSService());
        self::assertSame('svc', $d->OSService);

        $d->setGRPCUseTLS(true);
        self::assertTrue($d->isGRPCUseTLS());
        self::assertTrue($d->GRPCUseTLS);

        $d->setIntervalDuration('15s');
        self::assertInstanceOf(Time\Duration::class, $d->getIntervalDuration());
        self::assertInstanceOf(Time\Duration::class, $d->IntervalDuration);

        $d->setTimeoutDuration('10s');
        self::assertInstanceOf(Time\Duration::class, $d->getTimeoutDuration());
        self::assertInstanceOf(Time\Duration::class, $d->TimeoutDuration);

        $d->setDeregisterCriticalServiceAfterDuration('60s');
        self::assertInstanceOf(Time\Duration::class, $d->getDeregisterCriticalServiceAfterDuration());
        self::assertInstanceOf(Time\Duration::class, $d->DeregisterCriticalServiceAfterDuration);
    }

    public function testSetHeaderWithArray(): void
    {
        $d = new HealthCheckDefinition();
        $d->setHeader(['Content-Type' => ['application/json']]);
        self::assertInstanceOf(Values::class, $d->getHeader());
        self::assertSame('application/json', $d->Header->get('Content-Type'));
    }

    public function testSetHeaderWithNull(): void
    {
        $d = new HealthCheckDefinition();
        $d->setHeader(null);
        self::assertInstanceOf(Values::class, $d->getHeader());
        self::assertSame(0, $d->Header->count());
    }

    public function testSetHeaderWithValues(): void
    {
        $v = Values::fromArray(['X-Custom' => ['val']]);
        $d = new HealthCheckDefinition();
        $d->setHeader($v);
        self::assertSame($v, $d->getHeader());
        self::assertSame('val', $d->Header->get('X-Custom'));
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
            ->setGRPCUseTLS(true)
            ->setIntervalDuration('10s')
            ->setTimeoutDuration('5s')
            ->setDeregisterCriticalServiceAfterDuration('30s');
        self::assertSame($d, $result);
    }

    public function testJsonSerialize(): void
    {
        $d = new HealthCheckDefinition(
            HTTP: 'http://test',
            Method: 'GET',
            TLSSkipVerify: true,
        );
        $out = $d->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('http://test', $out->HTTP);
        self::assertSame('GET', $out->Method);
        self::assertTrue($out->TLSSkipVerify);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->HTTP = 'http://json';
        $decoded->Method = 'PUT';
        $decoded->Body = 'data';
        $decoded->TLSSkipVerify = true;
        $decoded->TCP = 'tcp-host';
        $decoded->TCPUseTLS = true;
        $decoded->UDP = 'udp-host';
        $decoded->GRPC = 'grpc-host';
        $decoded->OSService = 'os-svc';
        $decoded->GRPCUseTLS = true;
        $decoded->Interval = '10s';
        $decoded->Timeout = '5s';
        $decoded->DeregisterCriticalServiceAfter = '30s';

        $d = HealthCheckDefinition::jsonUnserialize($decoded);
        self::assertSame('http://json', $d->HTTP);
        self::assertSame('PUT', $d->Method);
        self::assertSame('data', $d->Body);
        self::assertTrue($d->TLSSkipVerify);
        self::assertSame('tcp-host', $d->TCP);
        self::assertTrue($d->TCPUseTLS);
        self::assertSame('udp-host', $d->UDP);
        self::assertSame('grpc-host', $d->GRPC);
        self::assertSame('os-svc', $d->OSService);
        self::assertTrue($d->GRPCUseTLS);
        self::assertInstanceOf(Time\Duration::class, $d->IntervalDuration);
        self::assertInstanceOf(Time\Duration::class, $d->TimeoutDuration);
        self::assertInstanceOf(Time\Duration::class, $d->DeregisterCriticalServiceAfterDuration);
    }

    public function testJsonUnserializeWithDurationKeys(): void
    {
        $decoded = new \stdClass();
        $decoded->HTTP = '';
        $decoded->Method = '';
        $decoded->Body = '';
        $decoded->TLSSkipVerify = false;
        $decoded->TCP = '';
        $decoded->TCPUseTLS = false;
        $decoded->UDP = '';
        $decoded->GRPC = '';
        $decoded->OSService = '';
        $decoded->GRPCUseTLS = false;
        $decoded->IntervalDuration = '15s';
        $decoded->TimeoutDuration = '10s';
        $decoded->DeregisterCriticalServiceAfterDuration = '60s';

        $d = HealthCheckDefinition::jsonUnserialize($decoded);
        self::assertInstanceOf(Time\Duration::class, $d->IntervalDuration);
        self::assertInstanceOf(Time\Duration::class, $d->TimeoutDuration);
        self::assertInstanceOf(Time\Duration::class, $d->DeregisterCriticalServiceAfterDuration);
    }

    public function testJsonRoundTrip(): void
    {
        $original = new HealthCheckDefinition(
            HTTP: 'http://rt',
            Method: 'GET',
            TLSSkipVerify: true,
            TCP: 'tcp',
            GRPCUseTLS: true,
        );
        $restored = HealthCheckDefinition::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->HTTP, $restored->HTTP);
        self::assertSame($original->Method, $restored->Method);
        self::assertSame($original->TLSSkipVerify, $restored->TLSSkipVerify);
        self::assertSame($original->TCP, $restored->TCP);
        self::assertSame($original->GRPCUseTLS, $restored->GRPCUseTLS);
    }
}
