<?php namespace DCarbone\PHPConsulAPITests\Usage\KV;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;

/**
 * Class KVClientUsageTest
 * @package DCarbone\PHPConsulAPITests\Usage\KV
 */
class KVClientUsageTest extends \PHPUnit_Framework_TestCase {

    const KVKey1 = 'testkey1';
    const KVValue1 = 'testvalue1';

    const KVKey2 = 'testkey2';
    const KVValue2 = 'testvalue2';

    const KVKey3 = 'testkey3';
    const KVValue3 = 'testvalue3';

    const KVPrefix = 'tests';

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        ConsulManager::startSingle();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        ConsulManager::stopSingle();
    }

    /**
     * @return KVClient
     */
    public function testCanConstructClient() {
        $kv = new KVClient(new Config());
        $this->assertInstanceOf(KVClient::class, $kv);
        return $kv;
    }

    /**
     * @depends testCanConstructClient
     */
    public function testCanPutKey() {
        $client = new KVClient(new Config());

        list($wm, $err) = $client->put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));
        $this->assertNull($err, sprintf('Unable to set kvp: %s', (string)$err));
        $this->assertInstanceOf(WriteMeta::class, $wm);
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetKey() {
        $client = new KVClient(new Config());
        $client->put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));

        list($kv, $qm, $err) = $client->get(self::KVKey1);
        $this->assertNull($err, sprintf('KV::get returned error: %s', (string)$err));
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertInstanceOf(KVPair::class, $kv);
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanDeleteKey() {
        $client = new KVClient(new Config());
        $client->put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));

        list($wm, $err) = $client->delete(self::KVKey1);
        $this->assertNull($err, sprintf('KV::delete returned error: %s', $err));
        $this->assertInstanceOf(
            WriteMeta::class,
            $wm,
            sprintf(
                'expected "%s", saw "%s"',
                WriteMeta::class,
                is_object($wm) ? get_class($wm) : gettype($wm)
            ));
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetNoPrefixList() {
        $client = new KVClient(new Config());
        $client->put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));
        $client->put(new KVPair(['Key' => self::KVKey2, 'Value' => self::KVValue2]));
        $client->put(new KVPair(['Key' => self::KVKey3, 'Value' => self::KVValue3]));

        list($list, $qm, $err) = $client->valueList();
        $this->assertNull($err, sprintf('KV::valueList returned error: %s', $err));
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertInternalType('array', $list);
        $this->assertCount(3, $list);
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetPrefixList() {
        $client = new KVClient(new Config());
        $client->put(new KVPair(['Key' => self::KVPrefix . '/' . self::KVKey1, 'Value' => self::KVValue1]));
        $client->put(new KVPair(['Key' => self::KVPrefix . '/' . self::KVKey2, 'Value' => self::KVValue2]));
        $client->put(new KVPair(['Key' => self::KVPrefix . '/' . self::KVKey3, 'Value' => self::KVValue3]));

        list($list, $qm, $err) = $client->valueList(self::KVPrefix);
        $this->assertNull($err, sprintf('KV::valueList returned error: %s', $err));
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertInternalType('array', $list);
        $this->assertCount(3, $list);
    }
}
