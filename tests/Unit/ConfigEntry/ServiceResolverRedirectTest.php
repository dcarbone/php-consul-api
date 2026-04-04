<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverRedirect;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceResolverRedirectTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $r = new ServiceResolverRedirect();
        self::assertSame('', $r->getService());
        self::assertSame('', $r->getServiceSubset());
        self::assertSame('', $r->getNamespace());
        self::assertSame('', $r->getPartition());
        self::assertSame('', $r->getDatacenter());
        self::assertSame('', $r->getPeer());
        self::assertSame('', $r->getSamenessGroup());
    }

    public function testConstructorWithParams(): void
    {
        $r = new ServiceResolverRedirect(
            Service: 'web-v2',
            ServiceSubset: 'v2',
            Namespace: 'ns',
            Partition: 'pt',
            Datacenter: 'dc2',
            Peer: 'peer1',
            SamenessGroup: 'sg',
        );
        self::assertSame('web-v2', $r->getService());
        self::assertSame('v2', $r->getServiceSubset());
        self::assertSame('ns', $r->getNamespace());
        self::assertSame('pt', $r->getPartition());
        self::assertSame('dc2', $r->getDatacenter());
        self::assertSame('peer1', $r->getPeer());
        self::assertSame('sg', $r->getSamenessGroup());
    }

    public function testFluentSetters(): void
    {
        $r = new ServiceResolverRedirect();
        $result = $r->setService('web')
            ->setServiceSubset('v1')
            ->setNamespace('ns')
            ->setPartition('pt')
            ->setDatacenter('dc1')
            ->setPeer('p1')
            ->setSamenessGroup('sg');
        self::assertSame($r, $result);
        self::assertSame('web', $r->getService());
        self::assertSame('v1', $r->getServiceSubset());
        self::assertSame('ns', $r->getNamespace());
        self::assertSame('pt', $r->getPartition());
        self::assertSame('dc1', $r->getDatacenter());
        self::assertSame('p1', $r->getPeer());
        self::assertSame('sg', $r->getSamenessGroup());
    }

}
