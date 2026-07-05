<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverFailoverTarget;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceResolverFailoverTargetTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $t = new ServiceResolverFailoverTarget();
        self::assertSame('', $t->getService());
        self::assertSame('', $t->getServiceSubset());
        self::assertSame('', $t->getPartition());
        self::assertSame('', $t->getNamespace());
        self::assertSame('', $t->getDatacenter());
        self::assertSame('', $t->getPeer());
    }

    public function testConstructorWithParams(): void
    {
        $t = new ServiceResolverFailoverTarget(
            Service: 'web',
            ServiceSubset: 'v1',
            Partition: 'pt',
            Namespace: 'ns',
            Datacenter: 'dc2',
            Peer: 'peer1',
        );
        self::assertSame('web', $t->getService());
        self::assertSame('v1', $t->getServiceSubset());
        self::assertSame('pt', $t->getPartition());
        self::assertSame('ns', $t->getNamespace());
        self::assertSame('dc2', $t->getDatacenter());
        self::assertSame('peer1', $t->getPeer());
    }

    public function testFluentSetters(): void
    {
        $t = new ServiceResolverFailoverTarget();
        $result = $t->setService('web')
            ->setServiceSubset('v1')
            ->setPartition('pt')
            ->setNamespace('ns')
            ->setDatacenter('dc2')
            ->setPeer('peer1');
        self::assertSame($t, $result);
        self::assertSame('web', $t->getService());
        self::assertSame('v1', $t->getServiceSubset());
        self::assertSame('pt', $t->getPartition());
        self::assertSame('ns', $t->getNamespace());
        self::assertSame('dc2', $t->getDatacenter());
        self::assertSame('peer1', $t->getPeer());
    }

}
