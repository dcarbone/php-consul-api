<?php

namespace DCarbone\PHPConsulAPITests\Usage\KV;

use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class KVClientCASTest
 *
 * @internal
 */
final class KVClientCASTest extends AbstractUsageTests
{
    public const KVKey1          = 'testcaskey';
    public const KVOriginalValue = 'originalvalue';
    public const KVUpdatedValue  = 'updatedvalue';
    public const KVUpdatedValue2 = 'updatedvalue2';

    /** @var bool */
    protected static $singlePerClass = true;

    public function testKVWithCAS(): void
    {
        /** @var \DCarbone\PHPConsulAPI\KV\KVPair $kv */
        /** @var \DCarbone\PHPConsulAPI\Error $err */
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVOriginalValue]));
        self::assertNull($err, \sprintf('Unable to put KV: %s', $err));

        [$kv, $_, $err] = $client->Get(self::KVKey1);
        self::assertNull($err, \sprintf('Unable to get KV: %s', $err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame(self::KVOriginalValue, $kv->Value);

        $omi = $kv->ModifyIndex;

        $kv->Value = self::KVUpdatedValue;

        [$ok, $_, $err] = $client->CAS($kv);
        self::assertNull($err, \sprintf('Unable to update kv value: %s', $err));
        self::assertTrue($ok);

        $kv->Value = self::KVUpdatedValue2;

        [$ok, $_, $err] = $client->CAS($kv);
        self::assertNull($err, \sprintf('Error updating kv with old cas: %s', $err));
        self::assertFalse($ok, 'Expected false when trying to update key with old cas');

        [$ok, $_, $err] = $client->DeleteCAS($kv);
        self::assertNull($err, \sprintf('Error deleting kv with old cas: %s', $err));
        self::assertFalse($ok, 'Expected false when trying to delete key with old cas');

        [$kv, $_, $err] = $client->Get(self::KVKey1);
        self::assertNull($err, \sprintf('Error retrieving updated key: %s', $err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertNotSame($omi, $kv->ModifyIndex, 'Expected ModifyIndex to be different');
        self::assertSame(self::KVUpdatedValue, $kv->Value, 'KV Value was not actually updated');

        [$ok, $_, $err] = $client->DeleteCAS($kv);
        self::assertNull($err, \sprintf('Error deleting key: %s', $err));
        self::assertTrue($ok, 'Expected true when deleting key with updated cas');
    }
}
