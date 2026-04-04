<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\GaugeValue;
use DCarbone\PHPConsulAPI\Agent\PointValue;
use DCarbone\PHPConsulAPI\Agent\SampledValue;
use DCarbone\PHPConsulAPI\Agent\MetricsInfo;
use DCarbone\PHPConsulAPI\Metrics\Label;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MetricsTypesTest extends TestCase
{
    // --- GaugeValue ---

    public function testGaugeValueDefaults(): void
    {
        $g = new GaugeValue();
        self::assertSame('', $g->getName());
        self::assertSame(0.0, $g->getValue());
        self::assertSame([], $g->getLabels());
    }

    public function testGaugeValueWithParams(): void
    {
        $label = new Label(Name: 'env', Value: 'prod');
        $g = new GaugeValue(Name: 'cpu', Value: 99.5, Labels: [$label]);
        self::assertSame('cpu', $g->getName());
        self::assertSame(99.5, $g->getValue());
        self::assertCount(1, $g->getLabels());
    }

    public function testGaugeValueJsonRoundTrip(): void
    {
        $g = new GaugeValue(Name: 'mem', Value: 42.0);
        $out = $g->jsonSerialize();
        self::assertSame('mem', $out->Name);
        self::assertSame(42.0, $out->Value);
    }

    // --- PointValue ---

    public function testPointValueDefaults(): void
    {
        $p = new PointValue();
        self::assertSame('', $p->getName());
        self::assertSame([], $p->getPoints());
    }

    public function testPointValueWithParams(): void
    {
        $p = new PointValue(Name: 'metric', Points: [1.0, 2.5, 3.7]);
        self::assertSame('metric', $p->getName());
        self::assertSame([1.0, 2.5, 3.7], $p->getPoints());
    }

    public function testPointValueJsonSerialize(): void
    {
        $p = new PointValue(Name: 'm', Points: [1.0]);
        $out = $p->jsonSerialize();
        self::assertSame('m', $out->Name);
        self::assertSame([1.0], $out->Points);
    }

    // --- SampledValue ---

    public function testSampledValueDefaults(): void
    {
        $s = new SampledValue();
        self::assertSame('', $s->getName());
        self::assertSame(0, $s->getCount());
        self::assertSame(0.0, $s->getSum());
        self::assertSame(0.0, $s->getMin());
        self::assertSame(0.0, $s->getMax());
        self::assertSame(0.0, $s->getMean());
        self::assertSame(0.0, $s->getStddev());
    }

    public function testSampledValueWithParams(): void
    {
        $s = new SampledValue(Name: 'req', Count: 10, Sum: 100.0, Min: 1.0, Max: 20.0, Mean: 10.0, Stddev: 3.5);
        self::assertSame('req', $s->getName());
        self::assertSame(10, $s->getCount());
        self::assertSame(100.0, $s->getSum());
    }

    public function testSampledValueJsonSerialize(): void
    {
        $s = new SampledValue(Name: 'req', Count: 5);
        $out = $s->jsonSerialize();
        self::assertSame('req', $out->Name);
        self::assertSame(5, $out->Count);
    }

    // --- MetricsInfo ---

    public function testMetricsInfoDefaults(): void
    {
        $m = new MetricsInfo();
        self::assertSame('', $m->getTimestamp());
        self::assertSame([], $m->getGauges());
        self::assertSame([], $m->getPoints());
        self::assertSame([], $m->getCounters());
        self::assertSame([], $m->getSamples());
    }

    public function testMetricsInfoWithParams(): void
    {
        $gauge = new GaugeValue(Name: 'cpu', Value: 50.0);
        $m = new MetricsInfo(Timestamp: '2025-01-01T00:00:00Z', Gauges: [$gauge]);
        self::assertSame('2025-01-01T00:00:00Z', $m->getTimestamp());
        self::assertCount(1, $m->getGauges());
    }

    public function testMetricsInfoJsonSerialize(): void
    {
        $m = new MetricsInfo(Timestamp: 'ts');
        $out = $m->jsonSerialize();
        self::assertSame('ts', $out->Timestamp);
    }
}

