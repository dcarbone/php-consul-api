<?php

declare(strict_types=1);

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
        self::assertSame('', $r->getNamespace());
        self::assertSame([], $r->getNodes());
        self::assertSame('', $r->getDatacenter());
        self::assertSame(0, $r->getFailovers());
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
        self::assertSame('ns', $r->getNamespace());
        self::assertSame('dc1', $r->getDatacenter());
        self::assertSame(2, $r->getFailovers());
    }

    public function testFluentSetters(): void
    {
        $r = new PreparedQueryExecuteResponse();
        $result = $r
            ->setService('svc')
            ->setNamespace('ns')
            ->setDatacenter('dc')
            ->setFailovers(1)
            ->setDNS(new QueryDNSOptions(TTL: '5s'));
        self::assertSame($r, $result);
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

