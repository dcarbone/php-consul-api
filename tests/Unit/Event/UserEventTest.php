<?php

namespace DCarbone\PHPConsulAPITests\Unit\Event;

use Brick\Math\BigInteger;
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
        self::assertSame('', $e->ID);
        self::assertSame('', $e->getName());
        self::assertSame('', $e->Name);
        self::assertSame('', $e->getPayload());
        self::assertSame('', $e->Payload);
        self::assertSame('', $e->getNodeFilter());
        self::assertSame('', $e->NodeFilter);
        self::assertSame('', $e->getServiceFilter());
        self::assertSame('', $e->ServiceFilter);
        self::assertSame('', $e->getTagFilter());
        self::assertSame('', $e->TagFilter);
        self::assertSame(0, $e->getVersion());
        self::assertSame(0, $e->Version);
        self::assertSame('0', (string)$e->getLTime());
        self::assertSame('0', (string)$e->LTime);
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
        self::assertSame('evt-1', $e->ID);
        self::assertSame('deploy', $e->getName());
        self::assertSame('deploy', $e->Name);
        self::assertSame('data', $e->getPayload());
        self::assertSame('data', $e->Payload);
        self::assertSame('web-*', $e->getNodeFilter());
        self::assertSame('web-*', $e->NodeFilter);
        self::assertSame('api', $e->getServiceFilter());
        self::assertSame('api', $e->ServiceFilter);
        self::assertSame('v1', $e->getTagFilter());
        self::assertSame('v1', $e->TagFilter);
        self::assertSame(3, $e->getVersion());
        self::assertSame(3, $e->Version);
        self::assertSame('42', (string)$e->getLTime());
        self::assertSame('42', (string)$e->LTime);
    }

    public function testSettersWithDirectFieldAccess(): void
    {
        $e = new UserEvent();

        $e->setID('id');
        self::assertSame('id', $e->getID());
        self::assertSame('id', $e->ID);

        $e->setName('name');
        self::assertSame('name', $e->getName());
        self::assertSame('name', $e->Name);

        $e->setPayload('payload');
        self::assertSame('payload', $e->getPayload());
        self::assertSame('payload', $e->Payload);

        $e->setNodeFilter('nf');
        self::assertSame('nf', $e->getNodeFilter());
        self::assertSame('nf', $e->NodeFilter);

        $e->setServiceFilter('sf');
        self::assertSame('sf', $e->getServiceFilter());
        self::assertSame('sf', $e->ServiceFilter);

        $e->setTagFilter('tf');
        self::assertSame('tf', $e->getTagFilter());
        self::assertSame('tf', $e->TagFilter);

        $e->setVersion(1);
        self::assertSame(1, $e->getVersion());
        self::assertSame(1, $e->Version);

        $e->setLTime(2);
        self::assertSame('2', (string)$e->getLTime());
        self::assertSame('2', (string)$e->LTime);
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
        self::assertSame('id', $e->ID);
        self::assertSame('name', $e->Name);
        self::assertSame('payload', $e->Payload);
        self::assertSame('nf', $e->NodeFilter);
        self::assertSame('sf', $e->ServiceFilter);
        self::assertSame('tf', $e->TagFilter);
        self::assertSame(1, $e->Version);
        self::assertSame('2', (string)$e->LTime);
    }

    public function testJsonSerialize(): void
    {
        $e = new UserEvent(ID: 'x', Name: 'test', Version: 5, LTime: 10);
        $out = $e->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('x', $out->ID);
        self::assertSame('test', $out->Name);
        self::assertSame(5, $out->Version);
        self::assertSame('10', (string)$out->LTime);
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
        self::assertSame('e1', $e->ID);
        self::assertSame('ev', $e->Name);
        self::assertSame('p', $e->Payload);
        self::assertSame(2, $e->Version);
        self::assertSame('7', (string)$e->LTime);
    }

    public function testJsonRoundTrip(): void
    {
        $original = new UserEvent(ID: 'rt', Name: 'round', Payload: 'trip', Version: 1, LTime: 3);
        $restored = UserEvent::jsonUnserialize($original->jsonSerialize());
        self::assertSame($original->ID, $restored->ID);
        self::assertSame($original->Name, $restored->Name);
        self::assertSame($original->Payload, $restored->Payload);
        self::assertSame($original->Version, $restored->Version);
        self::assertTrue($original->LTime->isEqualTo($restored->LTime));
    }

    public function testUint64LTime(): void
    {
        $max = '18446744073709551615';
        self::assertSame('1', (string)(new UserEvent(LTime: 1))->LTime);
        self::assertSame('2', (string)(new UserEvent(LTime: '2'))->getLTime());
        self::assertSame($max, (string)(new UserEvent(LTime: BigInteger::of($max)))->getLTime());

        $e = new UserEvent();
        $e->setLTime(3);
        self::assertSame('3', (string)$e->getLTime());
        $e->setLTime('4');
        self::assertSame('4', (string)$e->LTime);
        $e->setLTime(BigInteger::of($max));
        self::assertSame($max, (string)$e->getLTime());

        $d = new \stdClass();
        $d->LTime = $max;
        $e = UserEvent::jsonUnserialize($d);
        self::assertSame($max, (string)$e->getLTime());
        self::assertSame($max, (string)$e->jsonSerialize()->LTime);

        $this->expectException(\InvalidArgumentException::class);
        $e->setLTime('18446744073709551616');
    }
}

