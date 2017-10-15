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
use DCarbone\PHPConsulAPI\Error;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use PHPUnit\Framework\TestCase;

/**
 * Class KVClientCRUDTests
 * @package DCarbone\PHPConsulAPITests\Usage\KV
 */
class KVClientCRUDTests extends TestCase {

    const KVKey1 = 'testkey1';
    const KVValue1 = 'testvalue1';

    const KVKey2 = 'testkey2';
    const KVValue2 = 'testvalue2';

    const KVKey3 = 'testkey3';
    const KVValue3 = 'testvalue3';

    const KVPrefix = 'tests';

    protected function setUp() {
        ConsulManager::startSingle();
    }

    protected function tearDown() {
        ConsulManager::stopSingle();
    }

    public function testCanConstructClient() {
        $kv = new KVClient(new Config());
        $this->assertInstanceOf(KVClient::class, $kv);
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

    public function testListReturnsErrorWithInvalidPrefix() {
        $client = new KVClient(new Config());
        list($_, $_, $err) = $client->list(12345);
        $this->assertInstanceOf(
            Error::class,
            $err,
            sprintf(
                'Expected $err to be instanceof "%s", saw "%s"',
                Error::class,
                is_object($err) ? get_class($err) : gettype($err)
            ));
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetNoPrefixList() {
        /** @var \DCarbone\PHPConsulAPI\KV\KVPair[] $list */
        /** @var \DCarbone\PHPConsulAPI\QueryMeta $qm */
        /** @var \DCarbone\PHPConsulAPI\Error $err */

        $client = new KVClient(new Config());
        $client->put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));
        $client->put(new KVPair(['Key' => self::KVKey2, 'Value' => self::KVValue2]));
        $client->put(new KVPair(['Key' => self::KVKey3, 'Value' => self::KVValue3]));

        list($list, $qm, $err) = $client->list();
        $this->assertNull($err, sprintf('KV::valueList returned error: %s', $err));
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertInternalType('array', $list);
        $this->assertCount(3, $list);

        $key1found = false;
        $key2found = false;
        $key3found = false;

        foreach ($list as $kv) {
            if (self::KVValue1 === $kv->Value) {
                $key1found = true;
            } else if (self::KVValue2 === $kv->Value) {
                $key2found = true;
            } else if (self::KVValue3 === $kv->Value) {
                $key3found = true;
            }
        }

        try {
            $this->assertTrue($key1found, 'Key1 not found in list!');
            $this->assertTrue($key2found, 'Key2 not found in list!');
            $this->assertTrue($key3found, 'Key3 not found in list!');
        } catch (\PHPUnit_Framework_AssertionFailedError $e) {
            echo "\nno prefix \$list value:\n";
            var_dump($list);
            echo "\n";

            throw $e;
        }
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetPrefixList() {
        /** @var \DCarbone\PHPConsulAPI\KV\KVPair[] $list */
        /** @var \DCarbone\PHPConsulAPI\QueryMeta $qm */
        /** @var \DCarbone\PHPConsulAPI\Error $err */

        $client = new KVClient(new Config());
        $client->put(new KVPair(['Key' => self::KVPrefix.'/'.self::KVKey1, 'Value' => self::KVValue1]));
        $client->put(new KVPair(['Key' => self::KVPrefix.'/'.self::KVKey2, 'Value' => self::KVValue2]));
        $client->put(new KVPair(['Key' => self::KVPrefix.'/'.self::KVKey3, 'Value' => self::KVValue3]));

        list($list, $qm, $err) = $client->list(self::KVPrefix);
        $this->assertNull($err, sprintf('KV::valueList returned error: %s', $err));
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertInternalType('array', $list);
        $this->assertCount(3, $list);
        $this->assertContainsOnlyInstancesOf(KVPair::class, $list);

        $key1found = false;
        $key2found = false;
        $key3found = false;

        foreach ($list as $kv) {
            if (self::KVValue1 === $kv->Value) {
                $key1found = true;
            } else if (self::KVValue2 === $kv->Value) {
                $key2found = true;
            } else if (self::KVValue3 === $kv->Value) {
                $key3found = true;
            }
        }

        try {
            $this->assertTrue($key1found, 'Key1 not found in list!');
            $this->assertTrue($key2found, 'Key2 not found in list!');
            $this->assertTrue($key3found, 'Key3 not found in list!');
        } catch (\PHPUnit_Framework_AssertionFailedError $e) {
            echo "\nprefix \$list value:\n";
            var_dump($list);
            echo "\n";

            throw $e;
        }
    }

    public function testKeysReturnsErrorWithInvalidPrefix() {
        $client = new KVClient(new Config());
        list($_, $_, $err) = $client->keys(12345);
        $this->assertInstanceOf(
            Error::class,
            $err,
            sprintf(
                'Expected $err to be "%s", saw "%s"',
                Error::class,
                is_object($err) ? get_class($err) : gettype($err)
            ));
    }

    /**
     * @depends testCanPutKey
     */
    public function testCanGetNoPrefixKeys() {
        /** @var string[] $list */
        /** @var \DCarbone\PHPConsulAPI\QueryMeta $qm */
        /** @var \DCarbone\PHPConsulAPI\Error $err */

        $client = new KVClient(new Config());
        $client->put(new KVPair(['Key' => self::KVKey1, 'Value' => self::KVValue1]));
        $client->put(new KVPair(['Key' => self::KVKey2, 'Value' => self::KVValue2]));
        $client->put(new KVPair(['Key' => self::KVKey3, 'Value' => self::KVValue3]));

        list($list, $qm, $err) = $client->keys();
        $this->assertNull($err, sprintf('KV::keys returned error: %s', $err));
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertInternalType('array', $list);
        $this->assertCount(3, $list);
        $this->assertContainsOnly('string', $list, true);

        $key1found = false;
        $key2found = false;
        $key3found = false;

        foreach ($list as $key) {
            if (self::KVKey1 === $key) {
                $key1found = true;
            } else if (self::KVKey2 === $key) {
                $key2found = true;
            } else if (self::KVKey3 === $key) {
                $key3found = true;
            }
        }

        try {
            $this->assertTrue($key1found, 'Key1 not found in list!');
            $this->assertTrue($key2found, 'Key2 not found in list!');
            $this->assertTrue($key3found, 'Key3 not found in list!');
        } catch (\PHPUnit_Framework_AssertionFailedError $e) {
            echo "\nprefix \$list value:\n";
            var_dump($list);
            echo "\n";

            throw $e;
        }
    }
}
