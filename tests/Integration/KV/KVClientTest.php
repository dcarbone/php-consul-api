<?php

namespace DCarbone\PHPConsulAPITests\Integration\KV;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\KV\KVPairs;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use DCarbone\PHPConsulAPI\Txn\KVOp;
use DCarbone\PHPConsulAPI\Txn\KVTxnOp;
use DCarbone\PHPConsulAPI\Txn\TxnOp;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractIntegrationTestCase;

final class KVClientTest extends AbstractIntegrationTestCase
{
    protected bool $singlePerTest = true;

    public function testCanConstructClient(): void
    {
        $kv = new KVClient(ConsulManager::testConfig());
        self::assertInstanceOf(KVClient::class, $kv);
    }

    public function testCanConstructViaConsul(): void
    {
        $consul = new Consul(ConsulManager::testConfig());
        self::assertInstanceOf(KVClient::class, $consul->KV);
        self::assertInstanceOf(KVClient::class, $consul->KV());
    }

    public function testPut(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$wm, $err] = $client->Put(new KVPair(Key: 'test/put', Value: 'hello'));
        self::assertNull($err);
        self::assertInstanceOf(WriteMeta::class, $wm);
    }

    public function testPutWithFlags(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$wm, $err] = $client->Put(new KVPair(Key: 'test/flags', Value: 'flagged', Flags: 42));
        self::assertNull($err);
        self::assertInstanceOf(WriteMeta::class, $wm);

        [$kv, , $err] = $client->Get('test/flags');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame(42, $kv->Flags);
        self::assertSame('flagged', $kv->Value);
    }

    public function testGet(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/get';
        $value = 'bar';

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: $value));
        self::assertNull($err);

        [$kv, $qm, $err] = $client->Get($key);
        self::assertNull($err);
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

    public function testGetNotExist(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$kv, $qm, $err] = $client->Get('nonexistent/key');
        self::assertNull($err);
        self::assertNull($kv);
        self::assertInstanceOf(QueryMeta::class, $qm);
    }

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
            self::assertNull($err);
        }

        [$list, $qm, $err] = $client->List($prefix);
        self::assertNull($err);
        self::assertInstanceOf(KVPairs::class, $list);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertCount(3, $list);

        $found = [];
        foreach ($list as $pair) {
            self::assertInstanceOf(KVPair::class, $pair);
            $found[$pair->Key] = $pair->Value;
        }

        foreach ($keys as $k => $v) {
            self::assertArrayHasKey($k, $found);
            self::assertSame($v, $found[$k]);
        }
    }

    public function testListNoPrefix(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'alpha', Value: 'a'));
        self::assertNull($err);
        [$_, $err] = $client->Put(new KVPair(Key: 'beta', Value: 'b'));
        self::assertNull($err);

        [$list, $qm, $err] = $client->List();
        self::assertNull($err);
        self::assertInstanceOf(KVPairs::class, $list);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertGreaterThanOrEqual(2, count($list));
    }

    public function testKeys(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $prefix = 'test/keys';
        $keyNames = ["{$prefix}/a", "{$prefix}/b", "{$prefix}/c"];

        foreach ($keyNames as $k) {
            [$_, $err] = $client->Put(new KVPair(Key: $k, Value: 'v'));
            self::assertNull($err);
        }

        [$keys, $qm, $err] = $client->Keys($prefix);
        self::assertNull($err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($keys);
        self::assertCount(3, $keys);
        self::assertContainsOnly('string', $keys, true);

        foreach ($keyNames as $expected) {
            self::assertContains($expected, $keys);
        }
    }

    public function testKeysWithSeparator(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $prefix = 'test/keys-separator';
        $keyNames = ["{$prefix}/a", "{$prefix}/nested/b"];

        foreach ($keyNames as $k) {
            [$_, $err] = $client->Put(new KVPair(Key: $k, Value: 'v'));
            self::assertNull($err);
        }

        [$keys, $qm, $err] = $client->Keys($prefix, '/');
        self::assertNull($err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertCount(1, $keys);
        self::assertSame(["{$prefix}/"], $keys);
    }

    public function testKeysNoPrefix(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'keytest1', Value: 'v'));
        self::assertNull($err);
        [$_, $err] = $client->Put(new KVPair(Key: 'keytest2', Value: 'v'));
        self::assertNull($err);

        [$keys, $qm, $err] = $client->Keys();
        self::assertNull($err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($keys);
        self::assertGreaterThanOrEqual(2, count($keys));
        self::assertContains('keytest1', $keys);
        self::assertContains('keytest2', $keys);
    }

    public function testDelete(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/delete';

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'removeme'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        [$wm, $err] = $client->Delete($key);
        self::assertNull($err);
        self::assertInstanceOf(WriteMeta::class, $wm);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertNull($kv);
    }

    public function testDeleteTree(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $prefix = 'test/deltree';
        $keys = ["{$prefix}/1", "{$prefix}/2", "{$prefix}/sub/3"];

        foreach ($keys as $k) {
            [$_, $err] = $client->Put(new KVPair(Key: $k, Value: 'v'));
            self::assertNull($err);
        }

        [$list, , $err] = $client->List($prefix);
        self::assertNull($err);
        self::assertCount(3, $list);

        [$wm, $err] = $client->DeleteTree($prefix);
        self::assertNull($err);
        self::assertInstanceOf(WriteMeta::class, $wm);

        [$list, , $err] = $client->List($prefix);
        self::assertNull($err);
        self::assertCount(0, $list);
    }

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

    public function testPutEmptyValue(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/empty';

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: ''));
        self::assertNull($err);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame('', $kv->Value);
    }

    public function testDeleteNonExistent(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$wm, $err] = $client->Delete('nonexistent/delete');
        self::assertNull($err);
        self::assertInstanceOf(WriteMeta::class, $wm);
    }

    public function testCASSuccess(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'test/cas', Value: 'original'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get('test/cas');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame('original', $kv->Value);

        $kv->Value = 'updated';
        [$ok, $wm, $err] = $client->CAS($kv);
        self::assertNull($err);
        self::assertTrue($ok);
        self::assertInstanceOf(WriteMeta::class, $wm);
    }

    public function testCASFailsWithStaleIndex(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'test/cas-stale', Value: 'v1'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get('test/cas-stale');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        $staleIndex = $kv->ModifyIndex;

        [$_, $err] = $client->Put(new KVPair(Key: 'test/cas-stale', Value: 'v2'));
        self::assertNull($err);

        $kv->Value = 'v3';
        $kv->ModifyIndex = $staleIndex;
        [$ok, , $err] = $client->CAS($kv);
        self::assertNull($err);
        self::assertFalse($ok);

        [$kv, , $err] = $client->Get('test/cas-stale');
        self::assertNull($err);
        self::assertSame('v2', $kv->Value);
    }

    public function testCASWithFlags(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'test/cas-flags', Value: 'v1'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get('test/cas-flags');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        $kv->Value = 'v2';
        $kv->Flags = 99;
        [$ok, , $err] = $client->CAS($kv);
        self::assertNull($err);
        self::assertTrue($ok);

        [$kv2, , $err] = $client->Get('test/cas-flags');
        self::assertNull($err);
        self::assertSame('v2', $kv2->Value);
        self::assertSame(99, $kv2->Flags);
    }

    public function testDeleteCASSuccess(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'test/delcas', Value: 'v1'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get('test/delcas');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        [$ok, , $err] = $client->DeleteCAS($kv);
        self::assertNull($err);
        self::assertTrue($ok);

        [$kv, , $err] = $client->Get('test/delcas');
        self::assertNull($err);
        self::assertNull($kv);
    }

    public function testDeleteCASFailsWithStaleIndex(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'test/delcas-stale', Value: 'v1'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get('test/delcas-stale');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        $staleIndex = $kv->ModifyIndex;

        [$_, $err] = $client->Put(new KVPair(Key: 'test/delcas-stale', Value: 'v2'));
        self::assertNull($err);

        $kv->ModifyIndex = $staleIndex;
        [$ok, , $err] = $client->DeleteCAS($kv);
        self::assertNull($err);
        self::assertFalse($ok);

        [$kv2, , $err] = $client->Get('test/delcas-stale');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv2);
        self::assertSame('v2', $kv2->Value);
    }

    public function testCASFullLifecycle(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/cas-lifecycle';

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'original'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertSame('original', $kv->Value);
        $originalModifyIndex = $kv->ModifyIndex;

        $kv->Value = 'updated';
        [$ok, , $err] = $client->CAS($kv);
        self::assertNull($err);
        self::assertTrue($ok);

        $kv->Value = 'should-not-be-set';
        [$ok, , $err] = $client->CAS($kv);
        self::assertNull($err);
        self::assertFalse($ok);

        [$ok, , $err] = $client->DeleteCAS($kv);
        self::assertNull($err);
        self::assertFalse($ok);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertNotSame($originalModifyIndex, $kv->ModifyIndex);
        self::assertSame('updated', $kv->Value);

        [$ok, , $err] = $client->DeleteCAS($kv);
        self::assertNull($err);
        self::assertTrue($ok);
    }

    public function testAcquireAndRelease(): void
    {
        $conf          = ConsulManager::testConfig();
        $kvClient      = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        [$id, $_, $err] = $sessionClient->CreateNoChecks(
            new SessionEntry(
                Name: 'lock-test',
                LockDelay: new Time\Duration(0),
                Behavior: Consul::SessionBehaviorDelete,
                TTL: '10s',
            )
        );
        self::assertNull($err);
        self::assertNotEmpty($id);

        $key = 'test/lockable';

        $kv = new KVPair(Key: $key, Value: 'locked-value', Session: $id);
        [$ok, $wm, $err] = $kvClient->Acquire($kv);
        self::assertNull($err);
        self::assertTrue($ok);
        self::assertInstanceOf(WriteMeta::class, $wm);

        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame($id, $kv->Session);
        self::assertSame('locked-value', $kv->Value);
        self::assertGreaterThan(0, $kv->LockIndex);

        $kv->Session = $id;
        [$ok, $wm, $err] = $kvClient->Release($kv);
        self::assertNull($err);
        self::assertTrue($ok);
        self::assertInstanceOf(WriteMeta::class, $wm);

        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame('', $kv->Session);
        self::assertSame('locked-value', $kv->Value);
    }

    public function testAcquireConflict(): void
    {
        $conf          = ConsulManager::testConfig();
        $kvClient      = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        [$session1, , $err] = $sessionClient->CreateNoChecks(
            new SessionEntry(
                Name: 'lock-conflict-1',
                LockDelay: new Time\Duration(0),
                Behavior: Consul::SessionBehaviorRelease,
                TTL: '10s',
            )
        );
        self::assertNull($err);

        [$session2, , $err] = $sessionClient->CreateNoChecks(
            new SessionEntry(
                Name: 'lock-conflict-2',
                LockDelay: new Time\Duration(0),
                Behavior: Consul::SessionBehaviorRelease,
                TTL: '10s',
            )
        );
        self::assertNull($err);

        $key = 'test/lock-conflict';

        $kv = new KVPair(Key: $key, Value: 'session1', Session: $session1);
        [$ok, $wm, $err] = $kvClient->Acquire($kv);
        self::assertNull($err);
        self::assertTrue($ok);
        self::assertInstanceOf(WriteMeta::class, $wm);

        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err);
        self::assertSame($session1, $kv->Session);

        $kv2 = new KVPair(Key: $key, Value: 'session2', Session: $session2);
        [$ok, , $err] = $kvClient->Acquire($kv2);
        self::assertFalse($ok);
        self::assertNull($err);

        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err);
        self::assertSame($session1, $kv->Session);
    }

    public function testAcquireWithFlags(): void
    {
        $conf          = ConsulManager::testConfig();
        $kvClient      = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        [$id, , $err] = $sessionClient->CreateNoChecks(
            new SessionEntry(
                Name: 'lock-flags',
                LockDelay: new Time\Duration(0),
                Behavior: Consul::SessionBehaviorRelease,
                TTL: '10s',
            )
        );
        self::assertNull($err);

        $key = 'test/lock-flags';
        $kv = new KVPair(Key: $key, Flags: 77, Value: 'flagged', Session: $id);
        [$ok, , $err] = $kvClient->Acquire($kv);
        self::assertTrue($ok);
        self::assertNull($err);

        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame(77, $kv->Flags);
        self::assertSame($id, $kv->Session);
    }

    public function testTxnSet(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key   = 'test/txn/set';
        $value = 'txn-value';

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVSet,
                    Key: $key,
                    Value: $value,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertEmpty($txnResp->KVTxnResponse->Errors);
        self::assertNotEmpty($txnResp->KVTxnResponse->Results);

        $result = $txnResp->KVTxnResponse->Results[0];
        self::assertNotNull($result->KV);
        self::assertSame($key, $result->KV->Key);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame($value, $kv->Value);
    }

    public function testTxnGet(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key   = 'test/txn/get';
        $value = 'txn-get-value';

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: $value));
        self::assertNull($err);

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVGet,
                    Key: $key,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertEmpty($txnResp->KVTxnResponse->Errors);
        self::assertCount(1, $txnResp->KVTxnResponse->Results);

        $result = $txnResp->KVTxnResponse->Results[0];
        self::assertNotNull($result->KV);
        self::assertSame($key, $result->KV->Key);
        self::assertSame($value, $result->KV->Value);
    }

    public function testTxnMultipleOps(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $txnResp = $client->Txn(
            null,
            new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVSet, Key: 'test/txn/multi/a', Value: 'val-a')),
            new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVSet, Key: 'test/txn/multi/b', Value: 'val-b')),
            new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVSet, Key: 'test/txn/multi/c', Value: 'val-c')),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertEmpty($txnResp->KVTxnResponse->Errors);
        self::assertCount(3, $txnResp->KVTxnResponse->Results);

        [$kv, , $err] = $client->Get('test/txn/multi/a');
        self::assertNull($err);
        self::assertSame('val-a', $kv->Value);

        [$kv, , $err] = $client->Get('test/txn/multi/b');
        self::assertNull($err);
        self::assertSame('val-b', $kv->Value);

        [$kv, , $err] = $client->Get('test/txn/multi/c');
        self::assertNull($err);
        self::assertSame('val-c', $kv->Value);
    }

    public function testTxnDelete(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/delete';
        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'to-delete'));
        self::assertNull($err);

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVDelete,
                    Key: $key,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertNull($kv);
    }

    public function testTxnCAS(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/cas';
        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'v1'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVCAS,
                    Key: $key,
                    Value: 'v2',
                    Index: $kv->ModifyIndex,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertSame('v2', $kv->Value);
    }

    public function testTxnDeleteTree(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $prefix = 'test/txn/tree';
        [$_, $err] = $client->Put(new KVPair(Key: "{$prefix}/a", Value: 'va'));
        self::assertNull($err);
        [$_, $err] = $client->Put(new KVPair(Key: "{$prefix}/b", Value: 'vb'));
        self::assertNull($err);

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVDeleteTree,
                    Key: $prefix,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);

        [$list, , $err] = $client->List($prefix);
        self::assertNull($err);
        self::assertCount(0, $list);
    }

    public function testTxnCheckNotExists(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/check-not-exists';
        [$_, $err] = $client->Delete($key);
        self::assertNull($err);

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVCheckNotExists,
                    Key: $key,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);
    }

    public function testTxnSetAndGet(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/set-get';

        $txnResp = $client->Txn(
            null,
            new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVSet, Key: $key, Value: 'set-value')),
            new TxnOp(KV: new KVTxnOp(Verb: KVOp::KVGet, Key: $key)),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertEmpty($txnResp->KVTxnResponse->Errors);
        self::assertCount(2, $txnResp->KVTxnResponse->Results);

        $getResult = $txnResp->KVTxnResponse->Results[1];
        self::assertNotNull($getResult->KV);
        self::assertSame($key, $getResult->KV->Key);
        self::assertSame('set-value', $getResult->KV->Value);
    }

    public function testTxnSetWithFlags(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/flags';

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVSet,
                    Key: $key,
                    Value: 'flagged',
                    Flags: 123,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame('flagged', $kv->Value);
        self::assertSame(123, $kv->Flags);
    }

    public function testTxnGetOrEmptyMissingKey(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/get-or-empty-missing';
        [$_, $err] = $client->Delete($key);
        self::assertNull($err);

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVGetOrEmpty,
                    Key: $key,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertCount(1, $txnResp->KVTxnResponse->Results);
    }

    public function testTxnGetTree(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $prefix = 'test/txn/get-tree';
        [$_, $err] = $client->Put(new KVPair(Key: "{$prefix}/a", Value: 'va'));
        self::assertNull($err);
        [$_, $err] = $client->Put(new KVPair(Key: "{$prefix}/b", Value: 'vb'));
        self::assertNull($err);

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVGetTree,
                    Key: $prefix,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertGreaterThanOrEqual(2, count($txnResp->KVTxnResponse->Results));
    }

    public function testTxnDeleteCAS(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/delete-cas';
        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'delete-me'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVDeleteCAS,
                    Key: $key,
                    Index: $kv->ModifyIndex,
                ),
            ),
        );

        self::assertNull($txnResp->Err);
        self::assertTrue($txnResp->OK);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertNull($kv);
    }

    public function testTxnCheckIndex(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/check-index';
        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'v1'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        $index = $kv->ModifyIndex;

        $okResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVCheckIndex,
                    Key: $key,
                    Index: $index,
                ),
            ),
        );
        self::assertNull($okResp->Err);
        self::assertTrue($okResp->OK);
        self::assertNotNull($okResp->KVTxnResponse);
        self::assertEmpty($okResp->KVTxnResponse->Errors);

        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'v2'));
        self::assertNull($err);

        $conflictResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVCheckIndex,
                    Key: $key,
                    Index: $index,
                ),
            ),
        );
        self::assertNull($conflictResp->Err);
        self::assertNotNull($conflictResp->KVTxnResponse);
        self::assertNotEmpty($conflictResp->KVTxnResponse->Errors);
    }

    public function testTxnLockCheckSessionUnlock(): void
    {
        $conf = ConsulManager::testConfig();
        $client = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        [$sessionID, , $err] = $sessionClient->CreateNoChecks(new SessionEntry(Name: 'txn-lock-check-session'));
        self::assertNull($err);
        self::assertNotSame('', $sessionID);

        $key = 'test/txn/lock-unlock';

        $lockResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVLock,
                    Key: $key,
                    Value: 'locked',
                    Session: $sessionID,
                ),
            ),
        );
        self::assertNull($lockResp->Err);
        self::assertTrue($lockResp->OK);

        $checkResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVCheckSession,
                    Key: $key,
                    Session: $sessionID,
                ),
            ),
        );
        self::assertNull($checkResp->Err);
        self::assertTrue($checkResp->OK);
        self::assertNotNull($checkResp->KVTxnResponse);
        self::assertEmpty($checkResp->KVTxnResponse->Errors);

        $unlockResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVUnlock,
                    Key: $key,
                    Value: 'locked',
                    Session: $sessionID,
                ),
            ),
        );
        self::assertNull($unlockResp->Err);
        self::assertTrue($unlockResp->OK);
    }
}
