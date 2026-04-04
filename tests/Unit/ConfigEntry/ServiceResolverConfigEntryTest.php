<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverConfigEntry;
use DCarbone\PHPConsulAPI\ConfigEntry\ServiceResolverRedirect;
use DCarbone\PHPConsulAPI\ConfigEntry\LoadBalancer;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class ServiceResolverConfigEntryTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new ServiceResolverConfigEntry();
        self::assertSame('', $e->getKind());
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getPartition());
        self::assertSame('', $e->getDefaultSubset());
        self::assertNull($e->getRedirect());
        self::assertNull($e->getLoadBalancer());
    }

    public function testConstructorWithParams(): void
    {
        $redirect = new ServiceResolverRedirect(Service: 'web-v2', Datacenter: 'dc2');
        $lb = new LoadBalancer(Policy: 'ring_hash');
        $e = new ServiceResolverConfigEntry(
            Kind: 'service-resolver',
            Name: 'web',
            Partition: 'pt',
            DefaultSubnet: 'v1',
            Redirect: $redirect,
            LoadBalancer: $lb,
        );
        self::assertSame('service-resolver', $e->getKind());
        self::assertSame('web', $e->getName());
        self::assertSame('pt', $e->getPartition());
        self::assertSame($redirect, $e->getRedirect());
        self::assertSame($lb, $e->getLoadBalancer());
    }

    public function testFluentSetters(): void
    {
        $redirect = new ServiceResolverRedirect(Service: 'web-v2');
        $e = new ServiceResolverConfigEntry();
        $result = $e->setKind('service-resolver')
            ->setName('api')
            ->setPartition('pt')
            ->setRedirect($redirect);
        self::assertSame($e, $result);
        self::assertSame('service-resolver', $e->getKind());
        self::assertSame('api', $e->getName());
        self::assertSame($redirect, $e->getRedirect());
    }

}
