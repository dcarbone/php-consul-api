<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\ServiceAddress;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceAddressTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $sa = new ServiceAddress();
        self::assertSame('', $sa->getAddress());
        self::assertSame('', $sa->Address);
        self::assertSame(0, $sa->getPort());
        self::assertSame(0, $sa->Port);
    }

    public function testConstructorWithParams(): void
    {
        $sa = new ServiceAddress(Address: '10.0.0.1', Port: 8080);
        self::assertSame('10.0.0.1', $sa->getAddress());
        self::assertSame(8080, $sa->getPort());
    }

    public function testFluentSetters(): void
    {
        $sa = new ServiceAddress();
        $result = $sa->setAddress('a')->setPort(1);
        self::assertSame($sa, $result);
        self::assertSame('a', $sa->getAddress());
        self::assertSame('a', $sa->Address);
        self::assertSame(1, $sa->getPort());
        self::assertSame(1, $sa->Port);
    }

    public function testJsonSerialize(): void
    {
        $sa = new ServiceAddress(Address: 'a', Port: 1);
        $out = $sa->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('a', $out->Address);
        self::assertSame(1, $out->Port);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Address = 'b';
        $d->Port = 2;
        $sa = ServiceAddress::jsonUnserialize($d);
        self::assertInstanceOf(ServiceAddress::class, $sa);
        self::assertSame('b', $sa->getAddress());
        self::assertSame(2, $sa->getPort());
    }
}

