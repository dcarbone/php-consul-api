<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\PointValue;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PointValueTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new PointValue();
        self::assertSame('', $p->getName());
        self::assertSame('', $p->Name);
        self::assertSame([], $p->getPoints());
        self::assertSame([], $p->Points);
    }

    public function testConstructorWithParams(): void
    {
        $p = new PointValue(Name: 'metric', Points: [1.0, 2.5, 3.7]);
        self::assertSame('metric', $p->getName());
        self::assertSame('metric', $p->Name);
        self::assertSame([1.0, 2.5, 3.7], $p->getPoints());
    }

    public function testFluentSetters(): void
    {
        $p = new PointValue();
        $result = $p->setName('m')->setPoints(1.0, 2.0);
        self::assertSame($p, $result);
        self::assertSame('m', $p->getName());
        self::assertSame([1.0, 2.0], $p->getPoints());
    }

    public function testJsonSerialize(): void
    {
        $p = new PointValue(Name: 'm', Points: [1.0]);
        $out = $p->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('m', $out->Name);
        self::assertSame([1.0], $out->Points);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Name = 'pts';
        $d->Points = [3.0, 4.0];
        $p = PointValue::jsonUnserialize($d);
        self::assertInstanceOf(PointValue::class, $p);
        self::assertSame('pts', $p->getName());
        self::assertSame([3.0, 4.0], $p->getPoints());
    }
}

