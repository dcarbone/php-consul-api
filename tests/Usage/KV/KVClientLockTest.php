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
 * @package DCarbone\PHPConsulAPITests\Usage\KV
 */
class KVClientLockTest extends AbstractUsageTests {
    /** @var bool */
    protected static $singlePerClass = true;

    public function testAcquireAndRelease() {
        static $name = 'lockme';
        static $key = 'lockable';

        $conf = new Config();
        $kvClient = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        list($id, $_, $err) = $sessionClient->CreateNoChecks(new SessionEntry(['Name'      => $name,
                                                                               'TTL'       => '10s',
                                                                               'LockDelay' => new Time\Duration(0),
                                                                               'Behavior'  => Consul::SessionBehaviorDelete]));
        $this->assertNull($err, sprintf('Error creating session: %s', $err));

        $kv = new KVPair(['Key' => $key, 'Value' => 'whatever', 'Session' => $id]);
        list($wm, $err) = $kvClient->Acquire($kv);
        $this->assertNull($err, sprintf('Error acquiring lock: %s', $err));
        $this->assertInstanceOf(WriteMeta::class, $wm);

        list($kv, $_, $err) = $kvClient->Get($key);
        $this->assertNull($err, sprintf('Error retrieving key: %s', $err));
        $this->assertInstanceOf(KVPair::class, $kv);
        $this->assertEquals($id, $kv->Session);
        $this->assertEquals('whatever', $kv->Value);
    }
}