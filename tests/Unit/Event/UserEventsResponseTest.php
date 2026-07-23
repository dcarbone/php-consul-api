<?php

namespace DCarbone\PHPConsulAPITests\Unit\Event;

use DCarbone\PHPConsulAPI\Event\UserEvent;
use DCarbone\PHPConsulAPI\Event\UserEventsResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UserEventsResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new UserEventsResponse();
        self::assertSame([], $r->UserEvents);
        self::assertSame([], $r->getValue());
    }

    public function testUnmarshalValue(): void
    {
        $ev1 = new \stdClass();
        $ev1->ID = 'e1';
        $ev1->Name = 'deploy';
        $ev1->Payload = 'p1';
        $ev1->NodeFilter = '';
        $ev1->ServiceFilter = '';
        $ev1->TagFilter = '';
        $ev1->Version = 1;
        $ev1->LTime = 10;

        $ev2 = new \stdClass();
        $ev2->ID = 'e2';
        $ev2->Name = 'restart';
        $ev2->Payload = '';
        $ev2->NodeFilter = 'web';
        $ev2->ServiceFilter = '';
        $ev2->TagFilter = '';
        $ev2->Version = 2;
        $ev2->LTime = 20;

        $r = new UserEventsResponse();
        $r->unmarshalValue([$ev1, $ev2]);

        self::assertCount(2, $r->UserEvents);
        self::assertCount(2, $r->getValue());
        self::assertInstanceOf(UserEvent::class, $r->UserEvents[0]);
        self::assertInstanceOf(UserEvent::class, $r->UserEvents[1]);
        self::assertSame('e1', $r->UserEvents[0]->ID);
        self::assertSame('deploy', $r->UserEvents[0]->Name);
        self::assertSame('e2', $r->UserEvents[1]->ID);
        self::assertSame('restart', $r->UserEvents[1]->Name);
        self::assertSame('web', $r->UserEvents[1]->NodeFilter);
    }

    public function testUnmarshalValueResetsArray(): void
    {
        $ev = new \stdClass();
        $ev->ID = 'first';
        $ev->Name = 'ev';
        $ev->Payload = '';
        $ev->NodeFilter = '';
        $ev->ServiceFilter = '';
        $ev->TagFilter = '';
        $ev->Version = 1;
        $ev->LTime = 0;

        $r = new UserEventsResponse();
        $r->unmarshalValue([$ev]);
        self::assertCount(1, $r->UserEvents);

        $r->unmarshalValue([]);
        self::assertSame([], $r->UserEvents);
    }

    public function testUnmarshalValueNullKeepsDefaultArray(): void
    {
        $r = new UserEventsResponse();
        $r->unmarshalValue(null);

        self::assertSame([], $r->UserEvents);
        self::assertSame([], $r->getValue());
    }
}
