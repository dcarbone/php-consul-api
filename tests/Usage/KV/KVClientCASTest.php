<?php namespace DCarbone\PHPConsulAPITests\Usage\KV;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
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
        $client = new KVClient(new Config());

        [$_, $err] = $client->Put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVOriginalValue]));
        static::assertNull($err, \sprintf('Unable to put KV: %s', $err));

        [$kv, $_, $err] = $client->Get(self::KVKey1);
        static::assertNull($err, \sprintf('Unable to get KV: %s', $err));
        static::assertInstanceOf(KVPair::class, $kv);
        static::assertSame(self::KVOriginalValue, $kv->Value);

        $omi = $kv->ModifyIndex;

        $kv->Value = self::KVUpdatedValue;

        [$ok, $_, $err] = $client->CAS($kv);
        static::assertNull($err, \sprintf('Unable to update kv value: %s', $err));
        static::assertTrue($ok);

        $kv->Value = self::KVUpdatedValue2;

        [$ok, $_, $err] = $client->CAS($kv);
        static::assertNull($err, \sprintf('Error updating kv with old cas: %s', $err));
        static::assertFalse($ok, 'Expected false when trying to update key with old cas');

        [$ok, $_, $err] = $client->DeleteCAS($kv);
        static::assertNull($err, \sprintf('Error deleting kv with old cas: %s', $err));
        static::assertFalse($ok, 'Expected false when trying to delete key with old cas');

        [$kv, $_, $err] = $client->Get(self::KVKey1);
        static::assertNull($err, \sprintf('Error retrieving updated key: %s', $err));
        static::assertInstanceOf(KVPair::class, $kv);
        static::assertNotSame($omi, $kv->ModifyIndex, 'Expected ModifyIndex to be different');
        static::assertSame(self::KVUpdatedValue, $kv->Value, 'KV Value was not actually updated');

        [$ok, $_, $err] = $client->DeleteCAS($kv);
        static::assertNull($err, \sprintf('Error deleting key: %s', $err));
        static::assertTrue($ok, 'Expected true when deleting key with updated cas');
    }
}
