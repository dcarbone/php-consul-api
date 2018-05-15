<?php namespace DCarbone\PHPConsulAPITests\Usage\KV;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use PHPUnit\Framework\TestCase;

/**
 * Class KVClientLockTest
 * @package DCarbone\PHPConsulAPITests\Usage\KV
 */
class KVClientLockTest extends TestCase {

    protected function setUp() {
        ConsulManager::startSingle();
    }

    protected function tearDown() {
        ConsulManager::stopSingle();
    }

    public function testAcquireAndRelease() {
        static $name = 'lockme';
        static $key = 'lockable';

        $conf = new Config();
        $kvClient = new KVClient($conf);
        $sessionClient = new SessionClient($conf);

        list($id, $_, $err) = $sessionClient->CreateNoChecks(new SessionEntry(['Name'     => $name,
                                                                               'TTL'      => '10s',
                                                                               'Behavior' => Consul::SessionBehaviorDelete]));
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