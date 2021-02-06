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
 *
 * @internal
 */
final class SessionClientUsageTest extends AbstractUsageTests
{
    /** @var bool */
    protected static $singlePerClass = true;

    public function testNoChecksLifecycle(): void
    {
        static $name = 'testsession';
        static $ttl  = '10s';

        /** @var \DCarbone\PHPConsulAPI\Session\SessionEntry $session */
        /** @var \DCarbone\PHPConsulAPI\Session\SessionEntry[] $sessions */
        /** @var \DCarbone\PHPConsulAPI\Error $err */
        $client = new SessionClient(new Config());

        [$id, $wm, $err] = $client->CreateNoChecks(new SessionEntry([
            'Name'     => $name,
            'Behavior' => Consul::SessionBehaviorDelete,
            'TTL'      => $ttl,
        ]));
        static::assertNull($err, \sprintf('Error creating session: %s', $err));
        static::assertInstanceOf(WriteMeta::class, $wm);
        static::assertIsString($id, 'Expected ID to be string');

        [$sessions, $qm, $err] = $client->Info($id);
        static::assertNull($err, \sprintf('Error getting %s info: %s', $id, $err));
        static::assertInstanceOf(QueryMeta::class, $qm);
        static::assertIsArray($sessions);
        static::assertCount(1, $sessions);
        static::assertContainsOnly(SessionEntry::class, $sessions);
        static::assertSame($id, $sessions[0]->ID);
        static::assertSame($name, $sessions[0]->Name);
        static::assertSame($ttl, $sessions[0]->TTL);
        static::assertInstanceOf(Time\Duration::class, $sessions[0]->LockDelay);
        static::assertSame(Consul::SessionBehaviorDelete, $sessions[0]->Behavior);

        $session = $sessions[0];

        static::assertInstanceOf(Time\Duration::class, $session->LockDelay);
        static::assertSame(0, $session->LockDelay->Nanoseconds());

        [$sessions, $wm, $err] = $client->Renew($id);
        static::assertNull($err, \sprintf('Error renewing session: %s', $err));
        static::assertInstanceOf(WriteMeta::class, $wm);
        static::assertIsArray($sessions);
        static::assertCount(1, $sessions);
        static::assertContainsOnlyInstancesOf(SessionEntry::class, $sessions);

        [$_, $err] = $client->Destroy($id);
        static::assertNull($err, \sprintf('Error destroying session: %s', $err));

        [$sessions, $_, $err] = $client->Info($id);
        static::assertNull($err, \sprintf('Error getting list after expected expiration: %s', $err));
        static::assertNull($sessions, 'Expected $sessions to be null');
    }
}
