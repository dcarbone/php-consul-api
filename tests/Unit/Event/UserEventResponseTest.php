<?php

namespace DCarbone\PHPConsulAPITests\Unit\Event;

use DCarbone\PHPConsulAPI\Event\UserEvent;
use DCarbone\PHPConsulAPI\Event\UserEventResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UserEventResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new UserEventResponse();
        self::assertNull($r->UserEvent);
        self::assertNull($r->getValue());
    }

    public function testUnmarshalValueWithData(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'abc';
        $decoded->Name = 'deploy';
        $decoded->Payload = 'data';
        $decoded->NodeFilter = '';
        $decoded->ServiceFilter = '';
        $decoded->TagFilter = '';
        $decoded->Version = 1;
        $decoded->LTime = 5;

        $r = new UserEventResponse();
        $r->unmarshalValue($decoded);

        self::assertNotNull($r->UserEvent);
        self::assertNotNull($r->getValue());
        self::assertInstanceOf(UserEvent::class, $r->UserEvent);
        self::assertSame('abc', $r->UserEvent->ID);
        self::assertSame('deploy', $r->UserEvent->Name);
        self::assertSame('data', $r->UserEvent->Payload);
        self::assertSame(1, $r->UserEvent->Version);
        self::assertSame(5, $r->UserEvent->LTime);
    }

    public function testUnmarshalValueWithNull(): void
    {
        $r = new UserEventResponse();
        $r->unmarshalValue(null);
        self::assertNull($r->UserEvent);
        self::assertNull($r->getValue());
    }

    public function testUnmarshalValueOverwritesPrevious(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'first';
        $decoded->Name = 'ev1';
        $decoded->Payload = '';
        $decoded->NodeFilter = '';
        $decoded->ServiceFilter = '';
        $decoded->TagFilter = '';
        $decoded->Version = 1;
        $decoded->LTime = 0;

        $r = new UserEventResponse();
        $r->unmarshalValue($decoded);
        self::assertSame('first', $r->UserEvent->ID);

        $r->unmarshalValue(null);
        self::assertNull($r->UserEvent);
    }
}

