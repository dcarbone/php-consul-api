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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractUsageTests;

/**
 * Replicates the upstream Go TestAPI_ClientAcquireRelease test.
 *
 * @internal
 */
final class KVClientLockTest extends AbstractUsageTests
{
    protected static bool $singlePerClass = true;

    /**
     * Mirrors Go TestAPI_ClientAcquireRelease: create a session, acquire a lock,
     * verify the key is locked, release the lock, verify unlocked.
     */
    public function testAcquireAndRelease(): void
    {
        $conf          = ConsulManager::testConfig();
        $kvClient      = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        // Create session with no checks and short TTL
        [$id, $_, $err] = $sessionClient->CreateNoChecks(
            new SessionEntry(
                Name: 'lock-test',
                TTL: '10s',
                LockDelay: new Time\Duration(0),
                Behavior: Consul::SessionBehaviorDelete,
            )
        );
        self::assertNull($err, sprintf('Error creating session: %s', (string)$err));
        self::assertNotEmpty($id, 'Expected non-empty session ID');

        $key = 'test/lockable';

        // Acquire lock
        $kv = new KVPair(Key: $key, Value: 'locked-value', Session: $id);
        [$wm, $err] = $kvClient->Acquire($kv);
        self::assertNull($err, sprintf('Error acquiring lock: %s', (string)$err));
        self::assertInstanceOf(WriteMeta::class, $wm);

        // Verify the key is locked with the correct session
        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err, sprintf('Error getting key after acquire: %s', (string)$err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame($id, $kv->Session, 'Session ID should match after acquire');
        self::assertSame('locked-value', $kv->Value);
        self::assertGreaterThan(0, $kv->LockIndex, 'LockIndex should be > 0 after acquire');

        // Release lock
        $kv->Session = $id;
        [$wm, $err] = $kvClient->Release($kv);
        self::assertNull($err, sprintf('Error releasing lock: %s', (string)$err));
        self::assertInstanceOf(WriteMeta::class, $wm);

        // Verify the key is unlocked
        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err, sprintf('Error getting key after release: %s', (string)$err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame('', $kv->Session, 'Session should be empty after release');
        self::assertSame('locked-value', $kv->Value, 'Value should be preserved after release');
    }

    /**
     * Acquire lock, then attempt to acquire same key with a different session - should fail.
     */
    public function testAcquireConflict(): void
    {
        $conf          = ConsulManager::testConfig();
        $kvClient      = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        // Create two sessions
        [$session1, , $err] = $sessionClient->CreateNoChecks(
            new SessionEntry(
                Name: 'lock-conflict-1',
                TTL: '10s',
                LockDelay: new Time\Duration(0),
                Behavior: Consul::SessionBehaviorRelease,
            )
        );
        self::assertNull($err);

        [$session2, , $err] = $sessionClient->CreateNoChecks(
            new SessionEntry(
                Name: 'lock-conflict-2',
                TTL: '10s',
                LockDelay: new Time\Duration(0),
                Behavior: Consul::SessionBehaviorRelease,
            )
        );
        self::assertNull($err);

        $key = 'test/lock-conflict';

        // Session 1 acquires lock
        $kv = new KVPair(Key: $key, Value: 'session1', Session: $session1);
        [$wm, $err] = $kvClient->Acquire($kv);
        self::assertNull($err);
        self::assertInstanceOf(WriteMeta::class, $wm);

        // Verify session 1 holds the lock
        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err);
        self::assertSame($session1, $kv->Session);

        // Session 2 attempts to acquire the same lock - the key should still be held by session 1
        $kv2 = new KVPair(Key: $key, Value: 'session2', Session: $session2);
        [$_, $err] = $kvClient->Acquire($kv2);
        self::assertNull($err);

        // Verify session 1 still holds the lock
        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err);
        self::assertSame($session1, $kv->Session, 'Session 1 should still hold the lock');
    }

    /**
     * Acquire with flags should persist.
     */
    public function testAcquireWithFlags(): void
    {
        $conf          = ConsulManager::testConfig();
        $kvClient      = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        [$id, , $err] = $sessionClient->CreateNoChecks(
            new SessionEntry(
                Name: 'lock-flags',
                TTL: '10s',
                LockDelay: new Time\Duration(0),
                Behavior: Consul::SessionBehaviorRelease,
            )
        );
        self::assertNull($err);

        $key = 'test/lock-flags';
        $kv = new KVPair(Key: $key, Value: 'flagged', Session: $id, Flags: 77);
        [$_, $err] = $kvClient->Acquire($kv);
        self::assertNull($err);

        [$kv, , $err] = $kvClient->Get($key);
        self::assertNull($err);
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame(77, $kv->Flags);
        self::assertSame($id, $kv->Session);
    }
}
