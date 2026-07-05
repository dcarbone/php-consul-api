<?php

namespace DCarbone\PHPConsulAPITests\Unit\PreparedQuery;

use DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions;
use DCarbone\PHPConsulAPI\PreparedQuery\ServiceQuery;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ServiceQueryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new ServiceQuery();
        self::assertSame('', $s->getService());
        self::assertSame('', $s->Service);
        self::assertSame('', $s->getNamespace());
        self::assertSame('', $s->Namespace);
        self::assertSame('', $s->getNear());
        self::assertSame('', $s->Near);
        self::assertSame([], $s->getTags());
        self::assertSame([], $s->Tags);
        self::assertSame([], $s->getIgnoreCheckIDs());
        self::assertSame([], $s->IgnoreCheckIDs);
        self::assertInstanceOf(QueryDatacenterOptions::class, $s->getFailover());
        self::assertInstanceOf(QueryDatacenterOptions::class, $s->Failover);
        self::assertFalse($s->isOnlyPassing());
        self::assertFalse($s->OnlyPassing);
        self::assertSame([], $s->getNodeMeta());
        self::assertSame([], $s->NodeMeta);
        self::assertSame([], $s->getServiceMeta());
        self::assertSame([], $s->ServiceMeta);
        self::assertFalse($s->isConnect());
        self::assertFalse($s->Connect);
    }

    public function testConstructorWithValues(): void
    {
        $s = new ServiceQuery(
            Service: 'web',
            Namespace: 'ns',
            Near: '_agent',
            Tags: ['v1'],
            IgnoreCheckIDs: ['chk-1'],
            OnlyPassing: true,
            NodeMeta: ['env' => 'prod'],
            ServiceMeta: ['ver' => '2'],
            Connect: true,
        );
        self::assertSame('web', $s->getService());
        self::assertSame('web', $s->Service);
        self::assertSame('ns', $s->getNamespace());
        self::assertSame('ns', $s->Namespace);
        self::assertSame('_agent', $s->getNear());
        self::assertSame('_agent', $s->Near);
        self::assertSame(['v1'], $s->getTags());
        self::assertSame(['v1'], $s->Tags);
        self::assertSame(['chk-1'], $s->getIgnoreCheckIDs());
        self::assertSame(['chk-1'], $s->IgnoreCheckIDs);
        self::assertTrue($s->isOnlyPassing());
        self::assertTrue($s->OnlyPassing);
        self::assertSame(['env' => 'prod'], $s->getNodeMeta());
        self::assertSame(['env' => 'prod'], $s->NodeMeta);
        self::assertSame(['ver' => '2'], $s->getServiceMeta());
        self::assertSame(['ver' => '2'], $s->ServiceMeta);
        self::assertTrue($s->isConnect());
        self::assertTrue($s->Connect);
    }

    public function testFluentSetters(): void
    {
        $s = new ServiceQuery();
        $fo = new QueryDatacenterOptions(NearestN: 2);
        $result = $s
            ->setService('svc')
            ->setNamespace('ns')
            ->setNear('near')
            ->setTags('t1', 't2')
            ->setIgnoreCheckIDs('c1')
            ->setFailover($fo)
            ->setOnlyPassing(true)
            ->setNodeMeta(['k' => 'v'])
            ->setServiceMeta(['sk' => 'sv'])
            ->setConnect(true);
        self::assertSame($s, $result);
        self::assertSame('svc', $s->Service);
        self::assertSame('ns', $s->Namespace);
        self::assertSame('near', $s->Near);
        self::assertSame(['t1', 't2'], $s->Tags);
        self::assertSame(['c1'], $s->IgnoreCheckIDs);
        self::assertSame($fo, $s->Failover);
        self::assertTrue($s->OnlyPassing);
        self::assertSame(['k' => 'v'], $s->NodeMeta);
        self::assertSame(['sk' => 'sv'], $s->ServiceMeta);
        self::assertTrue($s->Connect);
    }

    public function testJsonSerialize(): void
    {
        $s = new ServiceQuery(Service: 'web');
        $out = $s->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('web', $out->Service);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->Service = 'api';
        $decoded->Namespace = '';
        $decoded->Near = '';
        $decoded->Tags = ['v2'];
        $decoded->IgnoreCheckIDs = [];
        $decoded->OnlyPassing = true;
        $decoded->Connect = false;

        $failover = new \stdClass();
        $failover->NearestN = 0;
        $failover->Datacenters = [];
        $decoded->Failover = $failover;

        $s = ServiceQuery::jsonUnserialize($decoded);
        self::assertSame('api', $s->getService());
        self::assertSame('api', $s->Service);
        self::assertSame(['v2'], $s->getTags());
        self::assertSame(['v2'], $s->Tags);
        self::assertTrue($s->isOnlyPassing());
        self::assertTrue($s->OnlyPassing);
    }
}

