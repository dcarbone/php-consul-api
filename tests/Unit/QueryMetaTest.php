<?php

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\QueryMeta;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class QueryMetaTest extends TestCase
{
    public function testConstructorWithRequiredParams(): void
    {
        $qm = new QueryMeta(RequestUrl: 'http://localhost/v1/kv/test', RequestTime: '5s');
        self::assertSame('http://localhost/v1/kv/test', $qm->getRequestUrl());
        self::assertSame('http://localhost/v1/kv/test', $qm->RequestUrl);
        self::assertSame(5.0, $qm->getRequestTime()->Seconds());
        self::assertSame(0, $qm->getLastIndex());
        self::assertSame(0, $qm->LastIndex);
        self::assertSame('', $qm->getLastContentHash());
        self::assertSame('', $qm->LastContentHash);
        self::assertSame(0, $qm->getLastContact());
        self::assertSame(0, $qm->LastContact);
        self::assertFalse($qm->isKnownLeader());
        self::assertFalse($qm->KnownLeader);
        self::assertFalse($qm->isAddressTranslationEnabled());
        self::assertFalse($qm->AddressTranslationEnabled);
        self::assertFalse($qm->isCacheHit());
        self::assertFalse($qm->CacheHit);
        self::assertSame(0.0, $qm->getCacheAge()->Seconds());
    }

    public function testConstructorWithAllParams(): void
    {
        $qm = new QueryMeta(
            RequestUrl: 'http://localhost/v1/catalog/services',
            RequestTime: '10s',
            LastIndex: 42,
            LastContentHash: 'abc123',
            LastContact: 15,
            KnownLeader: true,
            AddressTranslationEnabled: true,
            CacheHit: true,
            CacheAge: '30s',
        );
        self::assertSame('http://localhost/v1/catalog/services', $qm->getRequestUrl());
        self::assertSame(10.0, $qm->getRequestTime()->Seconds());
        self::assertSame(42, $qm->getLastIndex());
        self::assertSame(42, $qm->LastIndex);
        self::assertSame('abc123', $qm->getLastContentHash());
        self::assertSame('abc123', $qm->LastContentHash);
        self::assertSame(15, $qm->getLastContact());
        self::assertSame(15, $qm->LastContact);
        self::assertTrue($qm->isKnownLeader());
        self::assertTrue($qm->KnownLeader);
        self::assertTrue($qm->isAddressTranslationEnabled());
        self::assertTrue($qm->AddressTranslationEnabled);
        self::assertTrue($qm->isCacheHit());
        self::assertTrue($qm->CacheHit);
        self::assertSame(30.0, $qm->getCacheAge()->Seconds());
    }

    public function testFluentSetters(): void
    {
        $qm = new QueryMeta(RequestUrl: '', RequestTime: null);
        $result = $qm
            ->setRequestUrl('http://localhost/v1/agent/self')
            ->setRequestTime('2s')
            ->setLastIndex(100)
            ->setLastContentHash('hash')
            ->setLastContact(5)
            ->setKnownLeader(true)
            ->setAddressTranslationEnabled(true)
            ->setCacheHit(true)
            ->setCacheAge('60s');

        self::assertSame($qm, $result);
        self::assertSame('http://localhost/v1/agent/self', $qm->getRequestUrl());
        self::assertSame('http://localhost/v1/agent/self', $qm->RequestUrl);
        self::assertSame(2.0, $qm->getRequestTime()->Seconds());
        self::assertSame(100, $qm->getLastIndex());
        self::assertSame(100, $qm->LastIndex);
        self::assertSame('hash', $qm->getLastContentHash());
        self::assertSame('hash', $qm->LastContentHash);
        self::assertSame(5, $qm->getLastContact());
        self::assertSame(5, $qm->LastContact);
        self::assertTrue($qm->isKnownLeader());
        self::assertTrue($qm->KnownLeader);
        self::assertTrue($qm->isAddressTranslationEnabled());
        self::assertTrue($qm->AddressTranslationEnabled);
        self::assertTrue($qm->isCacheHit());
        self::assertTrue($qm->CacheHit);
        self::assertSame(60.0, $qm->getCacheAge()->Seconds());
    }

    public function testDurationCoercionFromNanoseconds(): void
    {
        $qm = new QueryMeta(
            RequestUrl: '',
            RequestTime: 3000000000,
            CacheAge: 1000000000,
        );
        self::assertSame(3.0, $qm->getRequestTime()->Seconds());
        self::assertSame(1.0, $qm->getCacheAge()->Seconds());
    }

    public function testSetCacheAgeWithNull(): void
    {
        $qm = new QueryMeta(RequestUrl: '', RequestTime: null, CacheAge: '10s');
        self::assertSame(10.0, $qm->getCacheAge()->Seconds());

        $qm->setCacheAge(null);
        self::assertSame(0.0, $qm->getCacheAge()->Seconds());
    }

    public function testSetRequestTimeWithNull(): void
    {
        $qm = new QueryMeta(RequestUrl: '', RequestTime: '5s');
        self::assertSame(5.0, $qm->getRequestTime()->Seconds());

        $qm->setRequestTime(null);
        self::assertSame(0.0, $qm->getRequestTime()->Seconds());
    }
}

