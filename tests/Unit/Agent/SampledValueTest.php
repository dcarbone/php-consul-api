<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\SampledValue;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SampledValueTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new SampledValue();
        self::assertSame('', $s->getName());
        self::assertSame('', $s->Name);
        self::assertSame(0, $s->getCount());
        self::assertSame(0, $s->Count);
        self::assertSame(0.0, $s->getSum());
        self::assertSame(0.0, $s->getMin());
        self::assertSame(0.0, $s->getMax());
        self::assertSame(0.0, $s->getMean());
        self::assertSame(0.0, $s->getStddev());
        self::assertSame([], $s->getLabels());
    }

    public function testConstructorWithParams(): void
    {
        $s = new SampledValue(
            Name: 'req',
            Count: 10,
            Sum: 100.0,
            Min: 1.0,
            Max: 20.0,
            Mean: 10.0,
            Stddev: 3.5,
            Labels: ['env' => 'prod'],
        );
        self::assertSame('req', $s->getName());
        self::assertSame(10, $s->getCount());
        self::assertSame(100.0, $s->getSum());
        self::assertSame(1.0, $s->getMin());
        self::assertSame(20.0, $s->getMax());
        self::assertSame(10.0, $s->getMean());
        self::assertSame(3.5, $s->getStddev());
        self::assertSame(['env' => 'prod'], $s->getLabels());
    }

    public function testFluentSetters(): void
    {
        $s = new SampledValue();
        $result = $s->setName('r')
            ->setCount(5)
            ->setSum(50.0)
            ->setMin(2.0)
            ->setMax(15.0)
            ->setMean(10.0)
            ->setStddev(2.0)
            ->setLabels(['k' => 'v']);
        self::assertSame($s, $result);
        self::assertSame('r', $s->getName());
        self::assertSame(5, $s->getCount());
        self::assertSame(50.0, $s->getSum());
        self::assertSame(2.0, $s->getMin());
        self::assertSame(15.0, $s->getMax());
        self::assertSame(10.0, $s->getMean());
        self::assertSame(2.0, $s->getStddev());
        self::assertSame(['k' => 'v'], $s->getLabels());
    }

    public function testSetLabelsNull(): void
    {
        $s = new SampledValue(Labels: ['a' => 'b']);
        $s->setLabels(null);
        // After setLabels(null), the property is unset; it becomes uninitialized.
        self::assertFalse(isset($s->Labels));
    }

    public function testJsonSerialize(): void
    {
        $s = new SampledValue(Name: 'req', Count: 5);
        $out = $s->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('req', $out->Name);
        self::assertSame(5, $out->Count);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Name = 'latency';
        $d->Count = 3;
        $d->Sum = 30.0;
        $d->Min = 5.0;
        $d->Max = 15.0;
        $d->Mean = 10.0;
        $d->Stddev = 4.0;
        $d->Labels = new \stdClass();
        $d->Labels->env = 'staging';
        $s = SampledValue::jsonUnserialize($d);
        self::assertInstanceOf(SampledValue::class, $s);
        self::assertSame('latency', $s->getName());
        self::assertSame(3, $s->getCount());
        self::assertSame(['env' => 'staging'], $s->getLabels());
    }
}

