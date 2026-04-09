<?php

namespace DCarbone\PHPConsulAPITests\Unit\PreparedQuery;

use DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryExecuteResponse;
use DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PreparedQueryExecuteResponseTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new PreparedQueryExecuteResponse();
        self::assertSame('', $r->getService());
        self::assertSame('', $r->Service);
        self::assertSame('', $r->getNamespace());
        self::assertSame('', $r->Namespace);
        self::assertSame([], $r->getNodes());
        self::assertSame([], $r->Nodes);
        self::assertInstanceOf(QueryDNSOptions::class, $r->getDNS());
        self::assertInstanceOf(QueryDNSOptions::class, $r->DNS);
        self::assertSame('', $r->getDatacenter());
        self::assertSame('', $r->Datacenter);
        self::assertSame(0, $r->getFailovers());
        self::assertSame(0, $r->Failovers);
    }

    public function testConstructorWithValues(): void
    {
        $r = new PreparedQueryExecuteResponse(
            Service: 'web',
            Namespace: 'ns',
            Datacenter: 'dc1',
            Failovers: 2,
        );
        self::assertSame('web', $r->getService());
        self::assertSame('web', $r->Service);
        self::assertSame('ns', $r->getNamespace());
        self::assertSame('ns', $r->Namespace);
        self::assertSame('dc1', $r->getDatacenter());
        self::assertSame('dc1', $r->Datacenter);
        self::assertSame(2, $r->getFailovers());
        self::assertSame(2, $r->Failovers);
    }

    public function testFluentSetters(): void
    {
        $r = new PreparedQueryExecuteResponse();
        $dns = new QueryDNSOptions(TTL: '5s');
        $result = $r
            ->setService('svc')
            ->setNamespace('ns')
            ->setDatacenter('dc')
            ->setFailovers(1)
            ->setDNS($dns);
        self::assertSame($r, $result);
        self::assertSame('svc', $r->Service);
        self::assertSame('ns', $r->Namespace);
        self::assertSame('dc', $r->Datacenter);
        self::assertSame(1, $r->Failovers);
        self::assertSame($dns, $r->DNS);
    }

    public function testJsonSerialize(): void
    {
        $r = new PreparedQueryExecuteResponse(Service: 'web', Datacenter: 'dc1');
        $out = $r->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('web', $out->Service);
        self::assertSame('dc1', $out->Datacenter);
    }
}

