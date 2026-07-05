<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\ServicePort;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServicePortTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new ServicePort();
        self::assertSame('', $p->getName());
        self::assertSame(0, $p->getPort());
        self::assertFalse($p->isDefault());
    }

    public function testConstructorWithParams(): void
    {
        $p = new ServicePort(Name: 'http', Port: 8080, Default: true);
        self::assertSame('http', $p->getName());
        self::assertSame('http', $p->Name);
        self::assertSame(8080, $p->getPort());
        self::assertTrue($p->isDefault());
    }

    public function testFluentSetters(): void
    {
        $p = new ServicePort();
        $result = $p->setName('grpc')->setPort(9090)->setDefault(true);
        self::assertSame($p, $result);
        self::assertSame('grpc', $p->getName());
        self::assertSame(9090, $p->getPort());
        self::assertTrue($p->isDefault());
    }

    public function testJsonSerialize(): void
    {
        $p = new ServicePort(Name: 'http', Port: 80, Default: true);
        $out = $p->jsonSerialize();
        self::assertSame('http', $out->Name);
        self::assertSame(80, $out->Port);
        self::assertTrue($out->Default);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Name = 'grpc';
        $d->Port = 9090;
        $d->Default = false;
        $p = ServicePort::jsonUnserialize($d);
        self::assertSame('grpc', $p->getName());
        self::assertSame(9090, $p->getPort());
        self::assertFalse($p->isDefault());
    }
}

