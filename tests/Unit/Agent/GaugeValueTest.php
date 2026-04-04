<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\GaugeValue;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GaugeValueTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $g = new GaugeValue();
        self::assertSame('', $g->getName());
        self::assertSame('', $g->Name);
        self::assertSame(0.0, $g->getValue());
        self::assertSame(0.0, $g->Value);
        self::assertSame([], $g->getLabels());
        self::assertSame([], $g->Labels);
    }

    public function testConstructorWithParams(): void
    {
        $g = new GaugeValue(Name: 'cpu', Value: 99.5, Labels: ['env' => 'prod']);
        self::assertSame('cpu', $g->getName());
        self::assertSame(99.5, $g->getValue());
        self::assertSame(['env' => 'prod'], $g->getLabels());
    }

    public function testFluentSetters(): void
    {
        $g = new GaugeValue();
        $result = $g->setName('mem')->setValue(42.0)->setLabels(['zone' => 'us']);
        self::assertSame($g, $result);
        self::assertSame('mem', $g->getName());
        self::assertSame('mem', $g->Name);
        self::assertSame(42.0, $g->getValue());
        self::assertSame(42.0, $g->Value);
        self::assertSame(['zone' => 'us'], $g->getLabels());
    }

    public function testSetLabelsNull(): void
    {
        $g = new GaugeValue(Name: 'cpu', Labels: ['a' => 'b']);
        $g->setLabels(null);
        // After setLabels(null), the property is null; getLabels returns null.
        self::assertNull($g->Labels);
    }

    public function testJsonSerialize(): void
    {
        $g = new GaugeValue(Name: 'mem', Value: 42.0, Labels: ['k' => 'v']);
        $out = $g->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('mem', $out->Name);
        self::assertSame(42.0, $out->Value);
        self::assertSame(['k' => 'v'], $out->Labels);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Name = 'cpu';
        $d->Value = 1.5;
        $d->Labels = new \stdClass();
        $d->Labels->env = 'prod';
        $g = GaugeValue::jsonUnserialize($d);
        self::assertInstanceOf(GaugeValue::class, $g);
        self::assertSame('cpu', $g->getName());
        self::assertSame(1.5, $g->getValue());
        self::assertSame(['env' => 'prod'], $g->getLabels());
    }
}

