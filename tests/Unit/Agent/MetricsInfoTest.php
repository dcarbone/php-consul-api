<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\GaugeValue;
use DCarbone\PHPConsulAPI\Agent\MetricsInfo;
use DCarbone\PHPConsulAPI\Agent\PointValue;
use DCarbone\PHPConsulAPI\Agent\SampledValue;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MetricsInfoTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $m = new MetricsInfo();
        self::assertSame('', $m->getTimestamp());
        self::assertSame('', $m->Timestamp);
        self::assertSame([], $m->getGauges());
        self::assertSame([], $m->getPoints());
        self::assertSame([], $m->getCounters());
        self::assertSame([], $m->getSamples());
    }

    public function testConstructorWithParams(): void
    {
        $gauge = new GaugeValue(Name: 'cpu', Value: 50.0);
        $point = new PointValue(Name: 'p', Points: [1.0]);
        $counter = new SampledValue(Name: 'c', Count: 1);
        $sample = new SampledValue(Name: 's', Count: 2);

        $m = new MetricsInfo(
            Timestamp: '2025-01-01T00:00:00Z',
            Gauges: [$gauge],
            Points: [$point],
            Counters: [$counter],
            Samples: [$sample],
        );
        self::assertSame('2025-01-01T00:00:00Z', $m->getTimestamp());
        self::assertCount(1, $m->getGauges());
        self::assertSame($gauge, $m->getGauges()[0]);
        self::assertCount(1, $m->getPoints());
        self::assertSame($point, $m->getPoints()[0]);
        self::assertCount(1, $m->getCounters());
        self::assertSame($counter, $m->getCounters()[0]);
        self::assertCount(1, $m->getSamples());
        self::assertSame($sample, $m->getSamples()[0]);
    }

    public function testFluentSetters(): void
    {
        $m = new MetricsInfo();
        $gauge = new GaugeValue(Name: 'g');
        $result = $m->setTimestamp('ts')
            ->setGauges($gauge)
            ->setPoints()
            ->setCounters()
            ->setSamples();
        self::assertSame($m, $result);
        self::assertSame('ts', $m->getTimestamp());
        self::assertCount(1, $m->getGauges());
        self::assertSame($gauge, $m->Gauges[0]);
    }

    public function testJsonSerialize(): void
    {
        $m = new MetricsInfo(Timestamp: 'ts');
        $out = $m->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('ts', $out->Timestamp);
    }

    public function testJsonUnserialize(): void
    {
        $d = new \stdClass();
        $d->Timestamp = '2025-01-01T00:00:00Z';

        $gObj = new \stdClass();
        $gObj->Name = 'cpu';
        $gObj->Value = 1.0;
        $gObj->Labels = new \stdClass();
        $d->Gauges = [$gObj];

        $pObj = new \stdClass();
        $pObj->Name = 'pt';
        $pObj->Points = [2.0];
        $d->Points = [$pObj];

        $cObj = new \stdClass();
        $cObj->Name = 'cnt';
        $cObj->Count = 5;
        $cObj->Sum = 0.0;
        $cObj->Min = 0.0;
        $cObj->Max = 0.0;
        $cObj->Mean = 0.0;
        $cObj->Stddev = 0.0;
        $cObj->Labels = new \stdClass();
        $d->Counters = [$cObj];
        $d->Samples = [];

        $m = MetricsInfo::jsonUnserialize($d);
        self::assertInstanceOf(MetricsInfo::class, $m);
        self::assertSame('2025-01-01T00:00:00Z', $m->getTimestamp());
        self::assertCount(1, $m->getGauges());
        self::assertInstanceOf(GaugeValue::class, $m->getGauges()[0]);
        self::assertSame('cpu', $m->getGauges()[0]->getName());
        self::assertCount(1, $m->getPoints());
        self::assertInstanceOf(PointValue::class, $m->getPoints()[0]);
        self::assertCount(1, $m->getCounters());
        self::assertInstanceOf(SampledValue::class, $m->getCounters()[0]);
    }
}

