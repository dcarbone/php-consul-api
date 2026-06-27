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
use DCarbone\PHPConsulAPI\Txn\KVOp;
use DCarbone\PHPConsulAPI\Txn\KVTxnOp;
use DCarbone\PHPConsulAPI\Txn\TxnOp;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractUsageTests;

/**
 * Replicates the upstream Go TestAPI_KVTxn test.
 *
 * @internal
 */
final class KVClientTxnTest extends AbstractUsageTests
{
    protected static bool $singlePerClass = true;

    /**
     * Mirrors Go TestAPI_KVTxn: set key via transaction, then read it back via Get.
     */
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

        self::assertNull($txnResp->Err, sprintf('KV::Txn set returned error: %s', (string)$txnResp->Err));
        self::assertTrue($txnResp->OK, 'Txn should be OK');
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertEmpty($txnResp->KVTxnResponse->Errors, 'Txn set should have no errors');
        self::assertNotEmpty($txnResp->KVTxnResponse->Results, 'Txn set should have results');

        // Verify the result KV pair
        $result = $txnResp->KVTxnResponse->Results[0];
        self::assertNotNull($result->KV);
        self::assertSame($key, $result->KV->Key);

        // Verify via regular Get
        [$kv, , $err] = $client->Get($key);
        self::assertNull($err, sprintf('KV::Get returned error: %s', (string)$err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame($value, $kv->Value);
    }

    /**
     * Get key via transaction.
     */
    public function testTxnGet(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key   = 'test/txn/get';
        $value = 'txn-get-value';

        // Put the key first
        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: $value));
        self::assertNull($err);

        // Get via Txn
        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVGet,
                    Key: $key,
                ),
            ),
        );

        self::assertNull($txnResp->Err, sprintf('KV::Txn get returned error: %s', (string)$txnResp->Err));
        self::assertTrue($txnResp->OK);
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertEmpty($txnResp->KVTxnResponse->Errors);
        self::assertCount(1, $txnResp->KVTxnResponse->Results);

        $result = $txnResp->KVTxnResponse->Results[0];
        self::assertNotNull($result->KV);
        self::assertSame($key, $result->KV->Key);
        self::assertSame($value, $result->KV->Value);
    }

    /**
     * Multiple operations in a single transaction.
     */
    public function testTxnMultipleOps(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVSet,
                    Key: 'test/txn/multi/a',
                    Value: 'val-a',
                ),
            ),
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVSet,
                    Key: 'test/txn/multi/b',
                    Value: 'val-b',
                ),
            ),
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVSet,
                    Key: 'test/txn/multi/c',
                    Value: 'val-c',
                ),
            ),
        );

        self::assertNull($txnResp->Err, sprintf('KV::Txn returned error: %s', (string)$txnResp->Err));
        self::assertTrue($txnResp->OK);
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertEmpty($txnResp->KVTxnResponse->Errors);
        self::assertCount(3, $txnResp->KVTxnResponse->Results);

        // Verify via Get
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

    /**
     * Delete via transaction.
     */
    public function testTxnDelete(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/delete';

        // Put key first
        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'to-delete'));
        self::assertNull($err);

        // Delete via Txn
        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVDelete,
                    Key: $key,
                ),
            ),
        );

        self::assertNull($txnResp->Err, sprintf('KV::Txn delete returned error: %s', (string)$txnResp->Err));
        self::assertTrue($txnResp->OK);

        // Verify deleted
        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertNull($kv, 'Expected null after Txn delete');
    }

    /**
     * CAS via transaction: set with ModifyIndex check.
     */
    public function testTxnCAS(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/cas';

        // Put initial value
        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'v1'));
        self::assertNull($err);

        // Get ModifyIndex
        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        // CAS via Txn with correct index
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

        // Verify updated
        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertSame('v2', $kv->Value);
    }

    /**
     * DeleteTree via transaction.
     */
    public function testTxnDeleteTree(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $prefix = 'test/txn/tree';

        // Put several keys under the prefix
        [$_, $err] = $client->Put(new KVPair(Key: "{$prefix}/a", Value: 'va'));
        self::assertNull($err);
        [$_, $err] = $client->Put(new KVPair(Key: "{$prefix}/b", Value: 'vb'));
        self::assertNull($err);

        // Delete tree via Txn
        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVDeleteTree,
                    Key: $prefix,
                ),
            ),
        );

        self::assertNull($txnResp->Err, sprintf('KV::Txn delete-tree returned error: %s', (string)$txnResp->Err));
        self::assertTrue($txnResp->OK);

        // Verify tree is gone
        [$list, , $err] = $client->List($prefix);
        self::assertNull($err);
        self::assertCount(0, $list);
    }

    /**
     * Check-not-exists via transaction.
     */
    public function testTxnCheckNotExists(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/check-not-exists';

        // Ensure key doesn't exist
        [$_, $err] = $client->Delete($key);
        self::assertNull($err);

        // check-not-exists should succeed when key doesn't exist
        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVCheckNotExists,
                    Key: $key,
                ),
            ),
        );

        self::assertNull($txnResp->Err, sprintf('KV::Txn returned error: %s', (string)$txnResp->Err));
        self::assertTrue($txnResp->OK);
    }

    /**
     * Set and get in the same transaction.
     */
    public function testTxnSetAndGet(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/txn/set-get';

        // Set then get in same txn
        $txnResp = $client->Txn(
            null,
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVSet,
                    Key: $key,
                    Value: 'set-value',
                ),
            ),
            new TxnOp(
                KV: new KVTxnOp(
                    Verb: KVOp::KVGet,
                    Key: $key,
                ),
            ),
        );

        self::assertNull($txnResp->Err, sprintf('KV::Txn returned error: %s', (string)$txnResp->Err));
        self::assertTrue($txnResp->OK);
        self::assertNotNull($txnResp->KVTxnResponse);
        self::assertEmpty($txnResp->KVTxnResponse->Errors);
        self::assertCount(2, $txnResp->KVTxnResponse->Results);

        // Second result should be the get with the value
        $getResult = $txnResp->KVTxnResponse->Results[1];
        self::assertNotNull($getResult->KV);
        self::assertSame($key, $getResult->KV->Key);
        self::assertSame('set-value', $getResult->KV->Value);
    }

    /**
     * Set with flags via transaction.
     */
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
}

