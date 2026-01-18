<?php

namespace DCarbone\PHPConsulAPITests\Usage\Session;

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Session\SessionEntry;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class SessionClientUsageTest
 *
 * @internal
 */
final class SessionClientUsageTest extends AbstractUsageTests
{
    /** @var bool */
    protected static bool $singlePerClass = true;

    public function testNoChecksLifecycle(): void
    {
        static $name = 'testsession';
        static $ttl  = '10s';

        /** @var \DCarbone\PHPConsulAPI\Session\SessionEntry $session */
        /** @var \DCarbone\PHPConsulAPI\Session\SessionEntry[] $sessions */
        /** @var \DCarbone\PHPConsulAPI\Error $err */
        $client = new SessionClient(ConsulManager::testConfig());

        [$id, $wm, $err] = $client->CreateNoChecks(new SessionEntry([
            'Name'     => $name,
            'Behavior' => Consul::SessionBehaviorDelete,
            'TTL'      => $ttl,
        ]));
        self::assertNull($err, sprintf('Error creating session: %s', $err));
        self::assertInstanceOf(WriteMeta::class, $wm);
        self::assertIsString($id, 'Expected ID to be string');

        [$sessions, $qm, $err] = $client->Info($id);
        self::assertNull($err, sprintf('Error getting %s info: %s', $id, $err));
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($sessions);
        self::assertCount(1, $sessions);
        self::assertContainsOnly(SessionEntry::class, $sessions);
        self::assertSame($id, $sessions[0]->ID);
        self::assertSame($name, $sessions[0]->Name);
        self::assertSame($ttl, $sessions[0]->TTL);
        self::assertInstanceOf(Time\Duration::class, $sessions[0]->LockDelay);
        self::assertSame(Consul::SessionBehaviorDelete, $sessions[0]->Behavior);

        $session = $sessions[0];

        self::assertInstanceOf(Time\Duration::class, $session->LockDelay);
        self::assertSame(15 * Time::Second, $session->LockDelay->Nanoseconds());

        [$sessions, $wm, $err] = $client->Renew($id);
        self::assertNull($err, sprintf('Error renewing session: %s', $err));
        self::assertInstanceOf(WriteMeta::class, $wm);
        self::assertIsArray($sessions);
        self::assertCount(1, $sessions);
        self::assertContainsOnlyInstancesOf(SessionEntry::class, $sessions);

        [$_, $err] = $client->Destroy($id);
        self::assertNull($err, sprintf('Error destroying session: %s', $err));

        [$sessions, $_, $err] = $client->Info($id);
        self::assertNull($err, sprintf('Error getting list after expected expiration: %s', $err));
        self::assertIsArray($sessions, 'Expected $sessions to be an array');
        self::assertCount(0, $sessions, 'Expected $sessions to be empty');
    }
}
