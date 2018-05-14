<?php namespace DCarbone\PHPConsulAPITests\Usage\KV;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class KVClientCASTest
 * @package DCarbone\PHPConsulAPITests\Usage\KV
 */
class KVClientCASTest extends AbstractUsageTests {
    /** @var bool */
    protected static $singlePerClass = true;

    const KVKey1          = 'testcaskey';
    const KVOriginalValue = 'originalvalue';
    const KVUpdatedValue  = 'updatedvalue';
    const KVUpdatedValue2 = 'updatedvalue2';

    public function testKVWithCAS() {
        /** @var \DCarbone\PHPConsulAPI\KV\KVPair $kv */
        /** @var \DCarbone\PHPConsulAPI\Error $err */

        $client = new KVClient(new Config());

        list($_, $err) = $client->put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVOriginalValue]));
        $this->assertNull($err, sprintf('Unable to put KV: %s', $err));

        list($kv, $_, $err) = $client->get(self::KVKey1);
        $this->assertNull($err, sprintf('Unable to get KV: %s', $err));
        $this->assertInstanceOf(KVPair::class, $kv);
        $this->assertEquals(self::KVOriginalValue, $kv->Value);

        $omi = $kv->ModifyIndex;

        $kv->Value = self::KVUpdatedValue;

        list($ok, $_, $err) = $client->cas($kv);
        $this->assertNull($err, sprintf('Unable to update kv value: %s', $err));
        $this->assertTrue($ok);

        $kv->Value = self::KVUpdatedValue2;

        list($ok, $_, $err) = $client->cas($kv);
        $this->assertNull($err, sprintf('Error updating kv with old cas: %s', $err));
        $this->assertFalse($ok, 'Expected false when trying to update key with old cas');

        list($ok, $_, $err) = $client->deleteCAS($kv);
        $this->assertNull($err, sprintf('Error deleting kv with old cas: %s', $err));
        $this->assertFalse($ok, 'Expected false when trying to delete key with old cas');

        list($kv, $_, $err) = $client->get(self::KVKey1);
        $this->assertNull($err, sprintf('Error retrieving updated key: %s', $err));
        $this->assertInstanceOf(KVPair::class, $kv);
        $this->assertNotEquals($omi, $kv->ModifyIndex, 'Expected ModifyIndex to be different');
        $this->assertEquals(self::KVUpdatedValue, $kv->Value, 'KV Value was not actually updated');


        list($ok, $_, $err) = $client->deleteCAS($kv);
        $this->assertNull($err, sprintf('Error deleting key: %s', $err));
        $this->assertTrue($ok, 'Expected true when deleting key with updated cas');
    }
}