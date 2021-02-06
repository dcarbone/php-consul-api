<?php namespace DCarbone\PHPConsulAPITests\Usage\KV;

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class KVClientLockTest
 *
 * @internal
 */
final class KVClientLockTest extends AbstractUsageTests
{
    /** @var bool */
    protected static $singlePerClass = true;

    public function testAcquireAndRelease(): void
    {
        static $name = 'lockme';
        static $key  = 'lockable';

        $conf          = new Config();
        $kvClient      = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        [$id, $_, $err] = $sessionClient->CreateNoChecks(new SessionEntry(['Name'      => $name,
            'TTL'                                                                      => '10s',
            'LockDelay'                                                                => new Time\Duration(0),
            'Behavior'                                                                 => Consul::SessionBehaviorDelete, ]));
        static::assertNull($err, \sprintf('Error creating session: %s', $err));

        $kv         = new KVPair(['Key' => $key, 'Value' => 'whatever', 'Session' => $id]);
        [$wm, $err] = $kvClient->Acquire($kv);
        static::assertNull($err, \sprintf('Error acquiring lock: %s', $err));
        static::assertInstanceOf(WriteMeta::class, $wm);

        [$kv, $_, $err] = $kvClient->Get($key);
        static::assertNull($err, \sprintf('Error retrieving key: %s', $err));
        static::assertInstanceOf(KVPair::class, $kv);
        static::assertSame($id, $kv->Session);
        static::assertSame('whatever', $kv->Value);
    }
}
