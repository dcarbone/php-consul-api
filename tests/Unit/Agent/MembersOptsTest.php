<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\MembersOpts;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MembersOptsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new MembersOpts();
        self::assertFalse($m->isWAN());
        self::assertFalse($m->WAN);
        self::assertSame('', $m->getSegment());
        self::assertSame('', $m->Segment);
        self::assertSame('', $m->getFilter());
        self::assertSame('', $m->Filter);
    }

    public function testConstructorWithParams(): void
    {
        $m = new MembersOpts(WAN: true, Segment: 'seg1', Filter: 'Status == "alive"');
        self::assertTrue($m->isWAN());
        self::assertTrue($m->WAN);
        self::assertSame('seg1', $m->getSegment());
        self::assertSame('seg1', $m->Segment);
        self::assertSame('Status == "alive"', $m->getFilter());
        self::assertSame('Status == "alive"', $m->Filter);
    }

    public function testFluentSetters(): void
    {
        $m = new MembersOpts();
        $result = $m->setWAN(true)->setSegment('s')->setFilter('f');
        self::assertSame($m, $result);
        self::assertTrue($m->isWAN());
        self::assertSame('s', $m->getSegment());
        self::assertSame('f', $m->getFilter());
    }

    public function testJsonSerializeOmitsDefaults(): void
    {
        $m = new MembersOpts();
        $out = $m->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertObjectNotHasProperty('WAN', $out);
        self::assertObjectNotHasProperty('Segment', $out);
        self::assertObjectNotHasProperty('Filter', $out);
    }

    public function testJsonSerializeWithValues(): void
    {
        $m = new MembersOpts(WAN: true, Segment: 'seg', Filter: 'f');
        $out = $m->jsonSerialize();
        self::assertTrue($out->WAN);
        self::assertSame('seg', $out->Segment);
        self::assertSame('f', $out->Filter);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->WAN = true;
        $d->Segment = 'seg';
        $d->Filter = 'f';
        $m = MembersOpts::jsonUnserialize($d);
        self::assertInstanceOf(MembersOpts::class, $m);
        self::assertTrue($m->isWAN());
        self::assertSame('seg', $m->getSegment());
        self::assertSame('f', $m->getFilter());
    }
}
