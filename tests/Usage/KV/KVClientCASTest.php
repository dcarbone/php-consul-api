<?php

namespace DCarbone\PHPConsulAPITests\Usage\KV;

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
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Replicates the upstream Go TestAPI_ClientCAS and TestAPI_ClientDeleteCAS tests.
 *
 * @internal
 */
final class KVClientCASTest extends AbstractUsageTests
{
    protected static bool $singlePerClass = true;

    /**
     * Mirrors Go TestAPI_ClientCAS: put, get, CAS with correct index succeeds,
     * CAS with stale index fails.
     */
    public function testCASSuccess(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        // Create initial key
        [$_, $err] = $client->Put(new KVPair(Key: 'test/cas', Value: 'original'));
        self::assertNull($err, sprintf('KV::Put returned error: %s', (string)$err));

        // Fetch to get ModifyIndex
        [$kv, , $err] = $client->Get('test/cas');
        self::assertNull($err, sprintf('KV::Get returned error: %s', (string)$err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame('original', $kv->Value);

        // CAS with correct ModifyIndex should succeed
        $kv->Value = 'updated';
        [$ok, $wm, $err] = $client->CAS($kv);
        self::assertNull($err, sprintf('KV::CAS returned error: %s', (string)$err));
        self::assertTrue($ok, 'CAS with correct ModifyIndex should succeed');
        self::assertInstanceOf(WriteMeta::class, $wm);
    }

    /**
     * CAS with stale ModifyIndex should fail (return false).
     */
    public function testCASFailsWithStaleIndex(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'test/cas-stale', Value: 'v1'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get('test/cas-stale');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        $staleIndex = $kv->ModifyIndex;

        // Update the key via regular Put to bump ModifyIndex
        [$_, $err] = $client->Put(new KVPair(Key: 'test/cas-stale', Value: 'v2'));
        self::assertNull($err);

        // Attempt CAS with the old (stale) ModifyIndex
        $kv->Value = 'v3';
        $kv->ModifyIndex = $staleIndex;
        [$ok, , $err] = $client->CAS($kv);
        self::assertNull($err, sprintf('KV::CAS returned error: %s', (string)$err));
        self::assertFalse($ok, 'CAS with stale ModifyIndex should fail');

        // Verify value was NOT changed
        [$kv, , $err] = $client->Get('test/cas-stale');
        self::assertNull($err);
        self::assertSame('v2', $kv->Value, 'Value should remain v2 after failed CAS');
    }

    /**
     * CAS with flags should persist flags on success.
     */
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

    /**
     * Mirrors Go TestAPI_ClientDeleteCAS: delete with correct and stale index.
     */
    public function testDeleteCASSuccess(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'test/delcas', Value: 'v1'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get('test/delcas');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        // DeleteCAS with correct index should succeed
        [$ok, , $err] = $client->DeleteCAS($kv);
        self::assertNull($err, sprintf('KV::DeleteCAS returned error: %s', (string)$err));
        self::assertTrue($ok, 'DeleteCAS with correct ModifyIndex should succeed');

        // Verify it's gone
        [$kv, , $err] = $client->Get('test/delcas');
        self::assertNull($err);
        self::assertNull($kv, 'Expected null after DeleteCAS');
    }

    /**
     * DeleteCAS with stale index should fail.
     */
    public function testDeleteCASFailsWithStaleIndex(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        [$_, $err] = $client->Put(new KVPair(Key: 'test/delcas-stale', Value: 'v1'));
        self::assertNull($err);

        [$kv, , $err] = $client->Get('test/delcas-stale');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);

        $staleIndex = $kv->ModifyIndex;

        // Bump the ModifyIndex
        [$_, $err] = $client->Put(new KVPair(Key: 'test/delcas-stale', Value: 'v2'));
        self::assertNull($err);

        // Attempt DeleteCAS with stale index
        $kv->ModifyIndex = $staleIndex;
        [$ok, , $err] = $client->DeleteCAS($kv);
        self::assertNull($err, sprintf('KV::DeleteCAS returned error: %s', (string)$err));
        self::assertFalse($ok, 'DeleteCAS with stale index should fail');

        // Verify key still exists
        [$kv2, , $err] = $client->Get('test/delcas-stale');
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv2);
        self::assertSame('v2', $kv2->Value);
    }

    /**
     * Full CAS lifecycle: put → get → CAS update → CAS stale → DeleteCAS stale → get → DeleteCAS current.
     * Mirrors the original combined test.
     */
    public function testCASFullLifecycle(): void
    {
        $client = new KVClient(ConsulManager::testConfig());

        $key = 'test/cas-lifecycle';

        // 1. Put initial value
        [$_, $err] = $client->Put(new KVPair(Key: $key, Value: 'original'));
        self::assertNull($err);

        // 2. Get to capture ModifyIndex
        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertSame('original', $kv->Value);
        $originalModifyIndex = $kv->ModifyIndex;

        // 3. CAS with correct index → success
        $kv->Value = 'updated';
        [$ok, , $err] = $client->CAS($kv);
        self::assertNull($err);
        self::assertTrue($ok);

        // 4. CAS with stale index → failure
        $kv->Value = 'should-not-be-set';
        [$ok, , $err] = $client->CAS($kv);
        self::assertNull($err);
        self::assertFalse($ok, 'CAS with old ModifyIndex should fail');

        // 5. DeleteCAS with stale index → failure
        [$ok, , $err] = $client->DeleteCAS($kv);
        self::assertNull($err);
        self::assertFalse($ok, 'DeleteCAS with old ModifyIndex should fail');

        // 6. Verify value is the successful update
        [$kv, , $err] = $client->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertNotSame($originalModifyIndex, $kv->ModifyIndex);
        self::assertSame('updated', $kv->Value);

        // 7. DeleteCAS with current index → success
        [$ok, , $err] = $client->DeleteCAS($kv);
        self::assertNull($err);
        self::assertTrue($ok, 'DeleteCAS with current ModifyIndex should succeed');
    }
}
