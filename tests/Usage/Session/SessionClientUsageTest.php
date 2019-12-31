<?php namespace DCarbone\PHPConsulAPITests\Usage\Session;

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class SessionClientUsageTest
 * @package DCarbone\PHPConsulAPITests\Usage\Session
 */
class SessionClientUsageTest extends AbstractUsageTests {
    /** @var bool */
    protected static $singlePerClass = true;

    public function testNoChecksLifecycle() {
        static $name = 'testsession';
        static $ttl = '10s';

        /** @var \DCarbone\PHPConsulAPI\Session\SessionEntry $session */
        /** @var \DCarbone\PHPConsulAPI\Session\SessionEntry[] $sessions */
        /** @var \DCarbone\PHPConsulAPI\Error $err */

        $client = new SessionClient(new Config());

        list($id, $wm, $err) = $client->CreateNoChecks(new SessionEntry([
            'Name'     => $name,
            'Behavior' => Consul::SessionBehaviorDelete,
            'TTL'      => $ttl,
        ]));
        $this->assertNull($err, sprintf('Error creating session: %s', $err));
        $this->assertInstanceOf(WriteMeta::class, $wm);
        $this->assertIsString($id, 'Expected ID to be string');

        list($sessions, $qm, $err) = $client->Info($id);
        $this->assertNull($err, sprintf('Error getting %s info: %s', $id, $err));
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertIsArray($sessions);
        $this->assertCount(1, $sessions);
        $this->assertContainsOnly(SessionEntry::class, $sessions);
        $this->assertEquals($id, $sessions[0]->ID);
        $this->assertEquals($name, $sessions[0]->Name);
        $this->assertEquals($ttl, $sessions[0]->TTL);
        $this->assertInstanceOf(Time\Duration::class, $sessions[0]->LockDelay);
        $this->assertEquals(Consul::SessionBehaviorDelete, $sessions[0]->Behavior);

        $session = $sessions[0];

        $this->assertInstanceOf(Time\Duration::class, $session->LockDelay);
        $this->assertEquals(0, $session->LockDelay->Nanoseconds());

        list($sessions, $wm, $err) = $client->Renew($id);
        $this->assertNull($err, sprintf('Error renewing session: %s', $err));
        $this->assertInstanceOf(WriteMeta::class, $wm);
        $this->assertIsArray($sessions);
        $this->assertCount(1, $sessions);
        $this->assertContainsOnlyInstancesOf(SessionEntry::class, $sessions);

        list($_, $err) = $client->Destroy($id);
        $this->assertNull($err, sprintf('Error destroying session: %s', $err));

        list($sessions, $_, $err) = $client->Info($id);
        $this->assertNull($err, sprintf('Error getting list after expected expiration: %s', $err));
        $this->assertNull($sessions, 'Expected $sessions to be null');
    }
}