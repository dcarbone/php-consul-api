<?php

namespace DCarbone\PHPConsulAPITests\Usage\KV;

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class KVClientLockTest
 *
 * @internal
 */
final class KVClientLockTest extends AbstractUsageTests
{
    /** @var bool */
    protected static bool $singlePerClass = true;

    public function testAcquireAndRelease(): void
    {
        static $name = 'lockme';
        static $key  = 'lockable';

        $conf          = ConsulManager::testConfig();
        $kvClient      = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        [$id, $_, $err] = $sessionClient->CreateNoChecks(
            new SessionEntry(
                [
                    'Name'      => $name,
                    'TTL'       => '10s',
                    'LockDelay' => new Time\Duration(0),
                    'Behavior'  => Consul::SessionBehaviorDelete,
                ]
            )
        );
        self::assertNull($err, \sprintf('Error creating session: %s', $err));

        $kv         = new KVPair(['Key' => $key, 'Value' => 'whatever', 'Session' => $id]);
        [$wm, $err] = $kvClient->Acquire($kv);
        self::assertNull($err, \sprintf('Error acquiring lock: %s', $err));
        self::assertInstanceOf(WriteMeta::class, $wm);

        [$kv, $_, $err] = $kvClient->Get($key);
        self::assertNull($err, \sprintf('Error retrieving key: %s', $err));
        self::assertInstanceOf(KVPair::class, $kv);
        self::assertSame($id, $kv->Session);
        self::assertSame('whatever', $kv->Value);
    }
}
