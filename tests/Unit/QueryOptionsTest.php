<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\PHPConsulAPI\QueryOptions;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class QueryOptionsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $qo = new QueryOptions();
        self::assertSame('', $qo->getNamespace());
        self::assertSame('', $qo->getDatacenter());
        self::assertFalse($qo->isAllowStale());
        self::assertFalse($qo->isRequireConsistent());
        self::assertFalse($qo->isUseCache());
        self::assertSame(0.0, $qo->getMaxAge()->Seconds());
        self::assertSame(0.0, $qo->getStaleIfError()->Seconds());
        self::assertSame(0, $qo->getWaitIndex());
        self::assertSame('', $qo->getWaitHash());
        self::assertSame(0.0, $qo->getWaitTime()->Seconds());
        self::assertSame('', $qo->getToken());
        self::assertSame('', $qo->getNear());
        self::assertSame('', $qo->getFilter());
        self::assertSame([], $qo->getNodeMeta());
        self::assertSame(0, $qo->getRelayFactor());
        self::assertFalse($qo->isLocalOnly());
        self::assertFalse($qo->isConnect());
        self::assertSame(0.0, $qo->getTimeout()->Seconds());
        self::assertFalse($qo->isPretty());
    }

    public function testConstructorWithValues(): void
    {
        $qo = new QueryOptions(
            Namespace: 'ns',
            Datacenter: 'dc1',
            AllowStale: true,
            Token: 'tok',
            Near: '_agent',
            Filter: 'Service.Tags contains "web"',
            NodeMeta: ['env' => 'prod'],
            RelayFactor: 3,
            Pretty: true,
        );
        self::assertSame('ns', $qo->getNamespace());
        self::assertSame('dc1', $qo->getDatacenter());
        self::assertTrue($qo->isAllowStale());
        self::assertSame('tok', $qo->getToken());
        self::assertSame('_agent', $qo->getNear());
        self::assertSame('Service.Tags contains "web"', $qo->getFilter());
        self::assertSame(['env' => 'prod'], $qo->getNodeMeta());
        self::assertSame(3, $qo->getRelayFactor());
        self::assertTrue($qo->isPretty());
    }

    public function testFluentSetters(): void
    {
        $qo = new QueryOptions();
        $result = $qo
            ->setNamespace('ns')
            ->setDatacenter('dc')
            ->setAllowStale(true)
            ->setRequireConsistent(true)
            ->setUseCache(true)
            ->setMaxAge('30s')
            ->setStaleIfError('60s')
            ->setWaitIndex(100)
            ->setWaitHash('abc')
            ->setWaitTime('5s')
            ->setToken('my-token')
            ->setNear('_agent')
            ->setFilter('f')
            ->setNodeMeta(['k' => 'v'])
            ->setRelayFactor(2)
            ->setLocalOnly(true)
            ->setConnect(true)
            ->setTimeout('15s')
            ->setPretty(true);

        self::assertSame($qo, $result);
        self::assertSame('ns', $qo->getNamespace());
        self::assertSame('dc', $qo->getDatacenter());
        self::assertTrue($qo->isAllowStale());
        self::assertTrue($qo->isRequireConsistent());
        self::assertTrue($qo->isUseCache());
        self::assertSame(30.0, $qo->getMaxAge()->Seconds());
        self::assertSame(60.0, $qo->getStaleIfError()->Seconds());
        self::assertSame(100, $qo->getWaitIndex());
        self::assertSame('abc', $qo->getWaitHash());
        self::assertSame(5.0, $qo->getWaitTime()->Seconds());
        self::assertSame('my-token', $qo->getToken());
        self::assertSame('_agent', $qo->getNear());
        self::assertSame('f', $qo->getFilter());
        self::assertSame(['k' => 'v'], $qo->getNodeMeta());
        self::assertSame(2, $qo->getRelayFactor());
        self::assertTrue($qo->isLocalOnly());
        self::assertTrue($qo->isConnect());
        self::assertSame(15.0, $qo->getTimeout()->Seconds());
        self::assertTrue($qo->isPretty());
    }

    public function testNodeMetaIsKeyedMap(): void
    {
        $qo = new QueryOptions(NodeMeta: ['env' => 'staging', 'region' => 'us-east']);
        $meta = $qo->getNodeMeta();

        self::assertArrayHasKey('env', $meta);
        self::assertArrayHasKey('region', $meta);
        self::assertSame('staging', $meta['env']);
        self::assertSame('us-east', $meta['region']);
    }

    public function testSetNodeMetaReplacesExisting(): void
    {
        $qo = new QueryOptions(NodeMeta: ['old' => 'val']);
        $qo->setNodeMeta(['new' => 'val2']);

        self::assertSame(['new' => 'val2'], $qo->getNodeMeta());
    }

    public function testDurationCoercionFromString(): void
    {
        $qo = new QueryOptions(MaxAge: '1m', StaleIfError: '2m', WaitTime: '30s', Timeout: '10s');
        self::assertSame(60.0, $qo->getMaxAge()->Seconds());
        self::assertSame(120.0, $qo->getStaleIfError()->Seconds());
        self::assertSame(30.0, $qo->getWaitTime()->Seconds());
        self::assertSame(10.0, $qo->getTimeout()->Seconds());
    }

    public function testDurationCoercionFromNanoseconds(): void
    {
        $qo = new QueryOptions(MaxAge: 5000000000); // 5s in nanoseconds
        self::assertSame(5.0, $qo->getMaxAge()->Seconds());
    }
}

