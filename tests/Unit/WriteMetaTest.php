<?php

namespace DCarbone\PHPConsulAPITests\Unit;

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\WriteMeta;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class WriteMetaTest extends TestCase
{
    public function testConstructorWithStringDuration(): void
    {
        $wm = new WriteMeta(RequestTime: '5s');
        self::assertSame(5.0, $wm->getRequestTime()->Seconds());
        self::assertSame(5.0, $wm->RequestTime->Seconds());
    }

    public function testConstructorWithNanoseconds(): void
    {
        $wm = new WriteMeta(RequestTime: 2000000000);
        self::assertSame(2.0, $wm->getRequestTime()->Seconds());
    }

    public function testConstructorWithNull(): void
    {
        $wm = new WriteMeta(RequestTime: null);
        self::assertSame(0.0, $wm->getRequestTime()->Seconds());
    }

    public function testFluentSetter(): void
    {
        $wm = new WriteMeta(RequestTime: null);
        $result = $wm->setRequestTime('10s');
        self::assertSame($wm, $result);
        self::assertSame(10.0, $wm->getRequestTime()->Seconds());
        self::assertSame(10.0, $wm->RequestTime->Seconds());
    }

    public function testSetRequestTimeWithNull(): void
    {
        $wm = new WriteMeta(RequestTime: '30s');
        self::assertSame(30.0, $wm->getRequestTime()->Seconds());

        $wm->setRequestTime(null);
        self::assertSame(0.0, $wm->getRequestTime()->Seconds());
    }

    public function testSetRequestTimeWithNanoseconds(): void
    {
        $wm = new WriteMeta(RequestTime: null);
        $wm->setRequestTime(7000000000);
        self::assertSame(7.0, $wm->getRequestTime()->Seconds());
    }

    public function testConstructorWithWarnings(): void
    {
        $wm = new WriteMeta('1s', 'warn-1', 'warn-2');
        self::assertSame(['warn-1', 'warn-2'], $wm->getWarnings());
        self::assertSame(['warn-1', 'warn-2'], $wm->Warnings);
    }

    public function testSetWarnings(): void
    {
        $wm = new WriteMeta(RequestTime: null);
        $result = $wm->setWarnings('warn-1', 'warn-2');
        self::assertSame($wm, $result);
        self::assertSame(['warn-1', 'warn-2'], $wm->getWarnings());
    }
}
