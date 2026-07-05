<?php

namespace DCarbone\PHPConsulAPITests\Unit\Catalog;

use DCarbone\PHPConsulAPI\Catalog\Weights;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class WeightsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $w = new Weights();
        self::assertSame(0, $w->getPassing());
        self::assertSame(0, $w->Passing);
        self::assertSame(0, $w->getWarning());
        self::assertSame(0, $w->Warning);
    }

    public function testConstructorWithParams(): void
    {
        $w = new Weights(Passing: 10, Warning: 1);
        self::assertSame(10, $w->getPassing());
        self::assertSame(1, $w->getWarning());
    }

    public function testFluentSetters(): void
    {
        $w = new Weights();
        $result = $w->setPassing(5)->setWarning(2);
        self::assertSame($w, $result);
        self::assertSame(5, $w->getPassing());
        self::assertSame(5, $w->Passing);
        self::assertSame(2, $w->getWarning());
        self::assertSame(2, $w->Warning);
    }

    public function testJsonSerialize(): void
    {
        $w = new Weights(Passing: 3, Warning: 1);
        $out = $w->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(3, $out->Passing);
        self::assertSame(1, $out->Warning);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Passing = 4;
        $d->Warning = 2;
        $w = Weights::jsonUnserialize($d);
        self::assertInstanceOf(Weights::class, $w);
        self::assertSame(4, $w->getPassing());
        self::assertSame(2, $w->getWarning());
    }
}

