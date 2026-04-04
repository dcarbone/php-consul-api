<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\SerfMember;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SerfMemberTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new SerfMember();
        self::assertSame('', $m->getID());
        self::assertSame('', $m->getName());
        self::assertSame('', $m->getAddr());
        self::assertSame(0, $m->getPort());
        self::assertSame('', $m->getDatacenter());
        self::assertSame('', $m->getRole());
        self::assertSame('', $m->getBuild());
        self::assertSame(0, $m->getProtocol());
        self::assertSame('', $m->getStatus());
        self::assertSame(0.0, $m->getRTT()->Seconds());
    }

    public function testConstructorWithValues(): void
    {
        $m = new SerfMember(
            ID: 'member-1',
            Name: 'node-1',
            Addr: '10.0.0.1',
            Port: 8301,
            Datacenter: 'dc1',
            Role: 'consul',
            Build: '1.22.0',
            Protocol: 2,
            Status: 'alive',
            RTT: '5ms',
        );
        self::assertSame('member-1', $m->getID());
        self::assertSame('node-1', $m->getName());
        self::assertSame('10.0.0.1', $m->getAddr());
        self::assertSame(8301, $m->getPort());
        self::assertSame('dc1', $m->getDatacenter());
        self::assertSame('consul', $m->getRole());
    }

    public function testFluentSetters(): void
    {
        $m = new SerfMember();
        $result = $m
            ->setID('i')
            ->setName('n')
            ->setAddr('a')
            ->setPort(1234)
            ->setDatacenter('dc')
            ->setRole('r')
            ->setBuild('b')
            ->setProtocol(2)
            ->setStatus('s')
            ->setRTT('10ms');
        self::assertSame($m, $result);
    }

    public function testJsonSerialize(): void
    {
        $m = new SerfMember(ID: 'x', Name: 'y', Port: 8301);
        $out = $m->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertSame('y', $out->Name);
        self::assertSame(8301, $out->Port);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'sm1';
        $decoded->Name = 'node';
        $decoded->Addr = '10.0.0.1';
        $decoded->Port = 8301;
        $decoded->Datacenter = 'dc1';
        $decoded->Role = 'consul';
        $decoded->Build = '1.22.0';
        $decoded->Protocol = 2;
        $decoded->Status = 'alive';
        $decoded->RTT = 5000000; // 5ms in nanoseconds

        $m = SerfMember::jsonUnserialize($decoded);
        self::assertSame('sm1', $m->getID());
        self::assertSame('node', $m->getName());
        self::assertSame(8301, $m->getPort());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new SerfMember(ID: 'rt', Name: 'n', Port: 1234);
        $json = json_encode($original);
        $restored = SerfMember::jsonUnserialize(json_decode($json, false));
        self::assertSame($original->getID(), $restored->getID());
        self::assertSame($original->getName(), $restored->getName());
        self::assertSame($original->getPort(), $restored->getPort());
    }
}

