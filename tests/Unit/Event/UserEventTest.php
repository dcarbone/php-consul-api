<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Event;

use DCarbone\PHPConsulAPI\Event\UserEvent;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UserEventTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $e = new UserEvent();
        self::assertSame('', $e->getID());
        self::assertSame('', $e->getName());
        self::assertSame('', $e->getPayload());
        self::assertSame('', $e->getNodeFilter());
        self::assertSame('', $e->getServiceFilter());
        self::assertSame('', $e->getTagFilter());
        self::assertSame(0, $e->getVersion());
        self::assertSame(0, $e->getLTime());
    }

    public function testConstructorWithValues(): void
    {
        $e = new UserEvent(
            ID: 'evt-1',
            Name: 'deploy',
            Payload: 'data',
            NodeFilter: 'web-*',
            ServiceFilter: 'api',
            TagFilter: 'v1',
            Version: 3,
            LTime: 42,
        );
        self::assertSame('evt-1', $e->getID());
        self::assertSame('deploy', $e->getName());
        self::assertSame('data', $e->getPayload());
        self::assertSame('web-*', $e->getNodeFilter());
        self::assertSame('api', $e->getServiceFilter());
        self::assertSame('v1', $e->getTagFilter());
        self::assertSame(3, $e->getVersion());
        self::assertSame(42, $e->getLTime());
    }

    public function testFluentSetters(): void
    {
        $e = new UserEvent();
        $result = $e
            ->setID('id')
            ->setName('name')
            ->setPayload('payload')
            ->setNodeFilter('nf')
            ->setServiceFilter('sf')
            ->setTagFilter('tf')
            ->setVersion(1)
            ->setLTime(2);
        self::assertSame($e, $result);
        self::assertSame('id', $e->getID());
        self::assertSame('name', $e->getName());
        self::assertSame('payload', $e->getPayload());
        self::assertSame('nf', $e->getNodeFilter());
        self::assertSame('sf', $e->getServiceFilter());
        self::assertSame('tf', $e->getTagFilter());
        self::assertSame(1, $e->getVersion());
        self::assertSame(2, $e->getLTime());
    }

    public function testJsonSerialize(): void
    {
        $e = new UserEvent(ID: 'x', Name: 'test', Version: 5, LTime: 10);
        $out = $e->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertSame('test', $out->Name);
        self::assertSame(5, $out->Version);
        self::assertSame(10, $out->LTime);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->ID = 'e1';
        $decoded->Name = 'ev';
        $decoded->Payload = 'p';
        $decoded->NodeFilter = '';
        $decoded->ServiceFilter = '';
        $decoded->TagFilter = '';
        $decoded->Version = 2;
        $decoded->LTime = 7;
        $e = UserEvent::jsonUnserialize($decoded);
        self::assertSame('e1', $e->getID());
        self::assertSame('ev', $e->getName());
        self::assertSame('p', $e->getPayload());
        self::assertSame(2, $e->getVersion());
        self::assertSame(7, $e->getLTime());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new UserEvent(ID: 'rt', Name: 'round', Payload: 'trip', Version: 1, LTime: 3);
        $restored = UserEvent::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->getID(), $restored->getID());
        self::assertSame($original->getName(), $restored->getName());
        self::assertSame($original->getPayload(), $restored->getPayload());
        self::assertSame($original->getVersion(), $restored->getVersion());
        self::assertSame($original->getLTime(), $restored->getLTime());
    }
}

