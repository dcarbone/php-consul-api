<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use Brick\Math\BigInteger;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ProxyMode;
use DCarbone\PHPConsulAPI\ConfigEntry\MutualTLSMode;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceConfigEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new ServiceConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getPartition());
        self::assertSame('', $e->getProtocol());
        self::assertSame(ProxyMode::Default, $e->getMode());
        self::assertNull($e->getTransparentProxy());
        self::assertSame(MutualTLSMode::Default, $e->getMutualTLSMode());
        self::assertSame('', $e->getExternalSNI());
        self::assertNull($e->getUpstreamConfig());
        self::assertNull($e->getDestination());
        self::assertSame(0, $e->getMaxInboundConnections());
        self::assertSame(0, $e->getLocalConnectTimeoutMs());
        self::assertSame(0, $e->getLocalRequestTimeoutMs());
        self::assertSame('', $e->getBalanceInboundConnections());
        self::assertNull($e->getRateLimits());
        self::assertSame([], $e->getEnvoyExtensions());
    }

    public function testConstructorWithParams(): void
    {
        $e = new ServiceConfigEntry(
            Kind: 'service-defaults',
            Name: 'web',
            Partition: 'pt',
            Protocol: 'http',
            Mode: ProxyMode::Transparent,
            MutualTLSMode: MutualTLSMode::Strict,
            ExternalSNI: 'web.example.com',
            MaxInboundConnections: 100,
        );
        self::assertSame('service-defaults', $e->getKind());
        self::assertSame('web', $e->getName());
        self::assertSame('pt', $e->getPartition());
        self::assertSame('http', $e->getProtocol());
        self::assertSame(ProxyMode::Transparent, $e->getMode());
        self::assertSame(MutualTLSMode::Strict, $e->getMutualTLSMode());
        self::assertSame('web.example.com', $e->getExternalSNI());
        self::assertSame(100, $e->getMaxInboundConnections());
    }

    public function testFluentSetters(): void
    {
        $e = new ServiceConfigEntry();
        $result = $e->setKind('service-defaults')
            ->setName('api')
            ->setPartition('pt')
            ->setProtocol('grpc')
            ->setMode(ProxyMode::Direct)
            ->setExternalSNI('api.example.com')
            ->setMaxInboundConnections(50)
            ->setLocalConnectTimeoutMs(5000)
            ->setLocalRequestTimeoutMs(10000)
            ->setBalanceInboundConnections('exact_balance');
        self::assertSame($e, $result);
        self::assertSame('service-defaults', $e->getKind());
        self::assertSame('api', $e->getName());
        self::assertSame('pt', $e->getPartition());
        self::assertSame('grpc', $e->getProtocol());
        self::assertSame(ProxyMode::Direct, $e->getMode());
        self::assertSame('api.example.com', $e->getExternalSNI());
        self::assertSame(50, $e->getMaxInboundConnections());
        self::assertSame(5000, $e->getLocalConnectTimeoutMs());
        self::assertSame(10000, $e->getLocalRequestTimeoutMs());
        self::assertSame('exact_balance', $e->getBalanceInboundConnections());
    }

    public function testConstructorWithEnumAsString(): void
    {
        $e = new ServiceConfigEntry(Mode: 'transparent', MutualTLSMode: 'strict');
        self::assertSame(ProxyMode::Transparent, $e->getMode());
        self::assertSame(MutualTLSMode::Strict, $e->getMutualTLSMode());
    }

    public function testIndexDefaults(): void
    {
        $e = new ServiceConfigEntry();
        self::assertTrue($e->getCreateIndex()->isZero());
        self::assertTrue($e->CreateIndex->isZero());
        self::assertTrue($e->getModifyIndex()->isZero());
        self::assertTrue($e->ModifyIndex->isZero());
    }

    public function testConstructorIndexesAcceptIntStringAndBigInteger(): void
    {
        $e = new ServiceConfigEntry(CreateIndex: 5, ModifyIndex: '18446744073709551615');
        self::assertSame('5', (string)$e->getCreateIndex());
        self::assertSame('5', (string)$e->CreateIndex);
        self::assertSame('18446744073709551615', (string)$e->getModifyIndex());
        self::assertSame('18446744073709551615', (string)$e->ModifyIndex);

        $big = BigInteger::of('9223372036854775808');
        $e = new ServiceConfigEntry(CreateIndex: $big, ModifyIndex: $big);
        self::assertTrue($e->CreateIndex->isEqualTo($big));
        self::assertTrue($e->ModifyIndex->isEqualTo($big));
    }

    public function testIndexSettersAcceptIntStringAndBigInteger(): void
    {
        $e = new ServiceConfigEntry();
        self::assertSame($e, $e->setCreateIndex(3));
        self::assertSame('3', (string)$e->CreateIndex);
        $e->setCreateIndex('18446744073709551615');
        self::assertSame('18446744073709551615', (string)$e->getCreateIndex());
        self::assertSame($e, $e->setModifyIndex(BigInteger::of(7)));
        self::assertSame('7', (string)$e->ModifyIndex);
    }

    public function testIndexSetterRejectsOutOfRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new ServiceConfigEntry())->setModifyIndex(-1);
    }

    public function testJsonUnserializeLargeIndexes(): void
    {
        $decoded = new \stdClass();
        $decoded->Kind = 'service-defaults';
        $decoded->Name = 'web';
        $decoded->CreateIndex = '18446744073709551615';
        $decoded->ModifyIndex = 12;
        $e = ServiceConfigEntry::jsonUnserialize($decoded);
        self::assertSame('18446744073709551615', (string)$e->CreateIndex);
        self::assertSame('12', (string)$e->ModifyIndex);

        $out = $e->jsonSerialize();
        self::assertInstanceOf(BigInteger::class, $out->CreateIndex);
        self::assertSame('18446744073709551615', (string)$out->CreateIndex);
    }
}
