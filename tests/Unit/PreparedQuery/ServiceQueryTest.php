<?php

declare(strict_types=1);

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
        self::assertSame('', $s->getNamespace());
        self::assertSame('', $s->getNear());
        self::assertSame([], $s->getTags());
        self::assertSame([], $s->getIgnoreCheckIDs());
        self::assertFalse($s->isOnlyPassing());
        self::assertFalse($s->isConnect());
    }

    public function testConstructorWithValues(): void
    {
        $s = new ServiceQuery(
            Service: 'web',
            Namespace: 'ns',
            Near: '_agent',
            Tags: ['v1'],
            OnlyPassing: true,
            Connect: true,
        );
        self::assertSame('web', $s->getService());
        self::assertSame('ns', $s->getNamespace());
        self::assertSame('_agent', $s->getNear());
        self::assertSame(['v1'], $s->getTags());
        self::assertTrue($s->isOnlyPassing());
        self::assertTrue($s->isConnect());
    }

    public function testFluentSetters(): void
    {
        $s = new ServiceQuery();
        $result = $s
            ->setService('svc')
            ->setNamespace('ns')
            ->setNear('near')
            ->setTags('t1', 't2')
            ->setIgnoreCheckIDs('c1')
            ->setOnlyPassing(true)
            ->setConnect(true);
        self::assertSame($s, $result);
        self::assertSame(['t1', 't2'], $s->getTags());
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
        self::assertSame(['v2'], $s->getTags());
        self::assertTrue($s->isOnlyPassing());
    }
}

