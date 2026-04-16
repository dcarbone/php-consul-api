<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\ForceLeaveOpts;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ForceLeaveOptsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $o = new ForceLeaveOpts();
        self::assertFalse($o->isPrune());
        self::assertFalse($o->isWAN());
    }

    public function testConstructorWithParams(): void
    {
        $o = new ForceLeaveOpts(Prune: true, WAN: true);
        self::assertTrue($o->isPrune());
        self::assertTrue($o->Prune);
        self::assertTrue($o->isWAN());
        self::assertTrue($o->WAN);
    }

    public function testFluentSetters(): void
    {
        $o = new ForceLeaveOpts();
        $result = $o->setPrune(true)->setWAN(true);
        self::assertSame($o, $result);
        self::assertTrue($o->isPrune());
        self::assertTrue($o->isWAN());
    }

    public function testJsonSerialize(): void
    {
        $o = new ForceLeaveOpts(Prune: true, WAN: false);
        $out = $o->jsonSerialize();
        self::assertTrue($out->Prune);
        self::assertFalse($out->WAN);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Prune = true;
        $d->WAN = true;
        $o = ForceLeaveOpts::jsonUnserialize($d);
        self::assertTrue($o->isPrune());
        self::assertTrue($o->isWAN());
    }
}

