<?php

namespace DCarbone\PHPConsulAPITests\Integration\Event;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Event\EventClient;
use DCarbone\PHPConsulAPI\Event\UserEvent;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractUsageTests;
use PHPUnit\Framework\Attributes\Depends;

/**
 * Class EventClientUsageTest
 *
 * @internal
 */
final class EventClientUsageTest extends AbstractUsageTests
{
    /** @var bool */
    protected static bool $singlePerClass = true;

    public function testCanConstructClient(): void
    {
        $client = new EventClient(ConsulManager::testConfig());
        self::assertInstanceOf(EventClient::class, $client);
    }

    #[Depends('testCanConstructClient')]
    public function testCanFireEvent(): UserEvent
    {
        $client = new EventClient(ConsulManager::testConfig());
        $name = sprintf('php-consul-api-event-%s', uniqid(prefix: '', more_entropy: true));

        [$event, $wm, $err] = $client->Fire(new UserEvent(
            Name: $name,
            Payload: 'integration-test-payload',
        ));

        self::assertNull($err, sprintf('EventClient::Fire returned error: %s', $err));
        self::assertInstanceOf(WriteMeta::class, $wm);
        self::assertInstanceOf(UserEvent::class, $event);
        self::assertSame($name, $event->Name);
        self::assertNotSame('', $event->ID);

        return $event;
    }

    #[Depends('testCanFireEvent')]
    public function testCanListEventByName(UserEvent $event): void
    {
        $client = new EventClient(ConsulManager::testConfig());

        [$events, $qm, $err] = $client->List(name: $event->Name);

        self::assertNull($err, sprintf('EventClient::List returned error: %s', $err));
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($events);
        self::assertContainsOnlyInstancesOf(UserEvent::class, $events);
        self::assertGreaterThanOrEqual(1, count($events));

        $matching = array_filter(
            $events,
            static fn(UserEvent $listed): bool => $listed->Name === $event->Name
        );

        self::assertGreaterThanOrEqual(1, count($matching));
    }

}
