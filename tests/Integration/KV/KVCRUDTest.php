<?php

namespace DCarbone\PHPConsulAPITests\Integration\KV;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\KV\KVPairs;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractUsageTests;

/**
 * Replicates the upstream Go TestAPI_ClientPut / TestAPI_ClientGet / TestAPI_ClientList /
 * TestAPI_ClientKeys / TestAPI_ClientDelete / TestAPI_ClientDeleteTree tests.
 *
 * @internal
 */
final class KVCRUDTest extends AbstractUsageTests
{
    protected bool $singlePerTest = true;

    // ---------------------------------------------------------------
    // PUT
    // ---------------------------------------------------------------

    /**
     * Mirrors Go TestAPI_ClientPut: put a simple key, verify no error.
     */
    public function testPut(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$wm, $err] = $client->Put(new KVPair(Key: 'test/put', Value: 'hello'));
        self::assertNull($err, sprintf('KV::Put returned error: %s', (string)$err));
        self::assertInstanceOf(WriteMeta::class, $wm);
    }

    /**
     * Mirrors Go TestAPI_ClientPut with flags set.
     */
    public function testPutWithFlags(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$wm, $err] = $client->Put(new KVPair(Key: 'test/flags', Value: 'flagged', Flags: 42));
        self::assertNull($err, sprintf('KV::Put returned error: %s', (string)$err));
        self::assertInstanceOf(WriteMeta::class, $wm);

        [$kv, , $err] = $client->Get('test/flags');
        self::assertNull($err, sprintf('KV::Get returned error: %s', (string)$err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame(42, $kv->Flags);
        self::assertSame('flagged', $kv->Value);
    }

    // ---------------------------------------------------------------
    // GET
    // ---------------------------------------------------------------

    /**
     * Mirrors Go TestAPI_ClientGet: put then get, verify value matches.
     */
    public function testGet(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/get';
        $value = 'bar';

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: $value));
        self::assertNull($err, sprintf('KV::Put returned error: %s', (string)$err));

        [$kv, $qm, $err] = $client->Get($key);
        self::assertNull($err, sprintf('KV::Get returned error: %s', (string)$err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertSame($key, $kv->Key);
        self::assertSame($value, $kv->Value);
        self::assertGreaterThan(0, $kv->CreateIndex);
        self::assertGreaterThan(0, $kv->ModifyIndex);
        self::assertSame(0, $kv->LockIndex);
        self::assertSame(0, $kv->Flags);
        self::assertSame('', $kv->Session);
        self::assertGreaterThan(0, $qm->LastIndex);
    }

    /**
     * Mirrors Go TestAPI_ClientGet for a non-existent key: returns nil KVPair, no error.
     */
    public function testGetNotExist(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$kv, $qm, $err] = $client->Get('nonexistent/key');
        self::assertNull($err, sprintf('KV::Get returned error: %s', (string)$err));
        self::assertNull($kv, 'Expected null KVPair for non-existent key');
        self::assertInstanceOf(QueryMeta::class, $qm);
    }

    // ---------------------------------------------------------------
    // LIST
    // ---------------------------------------------------------------

    /**
     * Mirrors Go TestAPI_ClientList: put multiple keys under a prefix, list them.
     */
    public function testList(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $prefix = 'test/list';
        $keys = [
            "{$prefix}/foo" => 'foo-value',
            "{$prefix}/bar" => 'bar-value',
            "{$prefix}/baz" => 'baz-value',
        ];

        foreach ($keys as $k => $v) {
            [$_, $err] = $client->Put(new KVPair(Key: $k, Value: $v));
            self::assertNull($err, sprintf('KV::Put(%s) returned error: %s', $k, (string)$err));
        }

        [$list, $qm, $err] = $client->List($prefix);
        self::assertNull($err, sprintf('KV::List returned error: %s', (string)$err));
        self::assertInstanceOf(KVPairs::class, $list);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertCount(3, $list);

        $found = [];
        foreach ($list as $pair) {
            self::assertInstanceOf(KVPair::class, $pair);
            $found[$pair->Key] = $pair->Value;
        }

        foreach ($keys as $k => $v) {
            self::assertArrayHasKey($k, $found, "Key {$k} not found in list");
            self::assertSame($v, $found[$k], "Value mismatch for key {$k}");
        }
    }

    /**
     * Mirrors Go TestAPI_ClientList with no prefix: list all keys in the store.
     */
    public function testListNoPrefix(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'alpha', Value: 'a'));
        self::assertNull($err);
        [$_, $err] = $client->Put(new KVPair(Key: 'beta', Value: 'b'));
        self::assertNull($err);

        [$list, $qm, $err] = $client->List();
        self::assertNull($err, sprintf('KV::List returned error: %s', (string)$err));
        self::assertInstanceOf(KVPairs::class, $list);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertGreaterThanOrEqual(2, count($list));
    }

    // ---------------------------------------------------------------
    // KEYS
    // ---------------------------------------------------------------

    /**
     * Mirrors Go TestAPI_ClientKeys: put multiple keys, get keys list.
     */
    public function testKeys(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $prefix = 'test/keys';
        $keyNames = ["{$prefix}/a", "{$prefix}/b", "{$prefix}/c"];

        foreach ($keyNames as $k) {
            [$_, $err] = $client->Put(new KVPair(Key: $k, Value: 'v'));
            self::assertNull($err, sprintf('KV::Put(%s) returned error: %s', $k, (string)$err));
        }

        [$keys, $qm, $err] = $client->Keys($prefix);
        self::assertNull($err, sprintf('KV::Keys returned error: %s', (string)$err));
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($keys);
        self::assertCount(3, $keys);
        self::assertContainsOnly('string', $keys, true);

        foreach ($keyNames as $expected) {
            self::assertContains($expected, $keys, "Expected key {$expected} not found");
        }
    }

    /**
     * Mirrors Go TestAPI_ClientKeys with no prefix: returns all keys.
     */
    public function testKeysNoPrefix(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'keytest1', Value: 'v'));
        self::assertNull($err);
        [$_, $err] = $client->Put(new KVPair(Key: 'keytest2', Value: 'v'));
        self::assertNull($err);

        [$keys, $qm, $err] = $client->Keys();
        self::assertNull($err, sprintf('KV::Keys returned error: %s', (string)$err));
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($keys);
        self::assertGreaterThanOrEqual(2, count($keys));
        self::assertContains('keytest1', $keys);
        self::assertContains('keytest2', $keys);
    }

    // ---------------------------------------------------------------
    // DELETE
    // ---------------------------------------------------------------

    /**
     * Mirrors Go TestAPI_ClientDelete: put, delete, verify gone.
     */
    public function testDelete(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/delete';

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'removeme'));
        self::assertNull($err, sprintf('KV::Put returned error: %s', (string)$err));

        // Verify it exists
        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        // Delete
        [$wm, $err] = $client->Delete($key);
        self::assertNull($err, sprintf('KV::Delete returned error: %s', (string)$err));
        self::assertInstanceOf(WriteMeta::class, $wm);

        // Verify it's gone
        [$kv, , $err] = $client->Get($key);
        self::assertNull($err, sprintf('KV::Get after delete returned error: %s', (string)$err));
        self::assertNull($kv, 'Expected null after deletion');
    }

    /**
     * Mirrors Go TestAPI_ClientDeleteTree: put a tree, delete tree, verify gone.
     */
    public function testDeleteTree(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $prefix = 'test/deltree';
        $keys = ["{$prefix}/1", "{$prefix}/2", "{$prefix}/sub/3"];

        foreach ($keys as $k) {
            [$_, $err] = $client->Put(new KVPair(Key: $k, Value: 'v'));
            self::assertNull($err, sprintf('KV::Put(%s) returned error: %s', $k, (string)$err));
        }

        // Verify they exist
        [$list, , $err] = $client->List($prefix);
        self::assertNull($err);
        self::assertCount(3, $list);

        // Delete tree
        [$wm, $err] = $client->DeleteTree($prefix);
        self::assertNull($err, sprintf('KV::DeleteTree returned error: %s', (string)$err));
        self::assertInstanceOf(WriteMeta::class, $wm);

        // Verify all gone - should return empty list
        [$list, , $err] = $client->List($prefix);
        self::assertNull($err, sprintf('KV::List after delete tree returned error: %s', (string)$err));
        self::assertCount(0, $list);
    }

    // ---------------------------------------------------------------
    // Combined read-after-write patterns
    // ---------------------------------------------------------------

    /**
     * Put a key, update it, verify the value and ModifyIndex changed.
     */
    public function testPutOverwrite(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/overwrite';

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'original'));
        self::assertNull($err);

        [$kv1, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertSame('original', $kv1->Value);

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'updated'));
        self::assertNull($err);

        [$kv2, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertSame('updated', $kv2->Value);
        self::assertGreaterThan($kv1->ModifyIndex, $kv2->ModifyIndex);
    }

    /**
     * Put empty value, verify it round-trips correctly.
     */
    public function testPutEmptyValue(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/empty';

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: ''));
        self::assertNull($err, sprintf('KV::Put returned error: %s', (string)$err));

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err, sprintf('KV::Get returned error: %s', (string)$err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame('', $kv->Value);
    }

    /**
     * Delete a key that doesn't exist - should succeed without error.
     */
    public function testDeleteNonExistent(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$wm, $err] = $client->Delete('nonexistent/delete');
        self::assertNull($err, sprintf('KV::Delete returned error: %s', (string)$err));
        self::assertInstanceOf(WriteMeta::class, $wm);
    }
}
