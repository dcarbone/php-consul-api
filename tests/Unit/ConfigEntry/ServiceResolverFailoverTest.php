<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailover;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverTarget;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverPolicy;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceResolverFailoverTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $f = new ServiceResolverFailover();
        self::assertSame('', $f->getService());
        self::assertSame('', $f->getServiceSubset());
        self::assertSame('', $f->getNamespace());
        self::assertSame([], $f->getDatacenters());
        self::assertSame([], $f->getTargets());
        self::assertNull($f->getPolicy());
        self::assertSame('', $f->getSamenessGroup());
    }

    public function testConstructorWithParams(): void
    {
        $target = new ServiceResolverFailoverTarget(Datacenter: 'dc2');
        $policy = new ServiceResolverFailoverPolicy(Mode: 'sequential');
        $f = new ServiceResolverFailover(
            Service: 'web',
            ServiceSubset: 'v1',
            Namespace: 'ns',
            Datacenters: ['dc2'],
            Targets: [$target],
            Policy: $policy,
            SamenessGroup: 'sg',
        );
        self::assertSame('web', $f->getService());
        self::assertSame('v1', $f->getServiceSubset());
        self::assertSame('ns', $f->getNamespace());
        self::assertSame(['dc2'], $f->getDatacenters());
        self::assertCount(1, $f->getTargets());
        self::assertSame($policy, $f->getPolicy());
        self::assertSame('sg', $f->getSamenessGroup());
    }

    public function testFluentSetters(): void
    {
        $f = new ServiceResolverFailover();
        $result = $f->setService('web')
            ->setServiceSubset('v1')
            ->setNamespace('ns')
            ->setDatacenters('dc1', 'dc2')
            ->setSamenessGroup('sg');
        self::assertSame($f, $result);
        self::assertSame('web', $f->getService());
        self::assertSame('v1', $f->getServiceSubset());
        self::assertSame('ns', $f->getNamespace());
        self::assertSame(['dc1', 'dc2'], $f->getDatacenters());
        self::assertSame('sg', $f->getSamenessGroup());
    }

    public function testJsonUnserializeHydratesTargets(): void
    {
        $target = new \stdClass();
        $target->Datacenter = 'dc2';

        $decoded = new \stdClass();
        $decoded->Service = 'web';
        $decoded->Targets = [$target];

        $f = ServiceResolverFailover::jsonUnserialize($decoded);
        self::assertSame('web', $f->getService());
        self::assertCount(1, $f->getTargets());
        self::assertSame('dc2', $f->getTargets()[0]->getDatacenter());
    }

}
