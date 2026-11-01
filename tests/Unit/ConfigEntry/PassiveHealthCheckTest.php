<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\PassiveHealthCheck;
use PHPUnit\Framework\TestCase;


/**
 * @internal
 */
final class PassiveHealthCheckTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $p = new PassiveHealthCheck();
        self::assertSame(0, $p->getInterval()->Nanoseconds());
        self::assertSame(0, $p->getMaxFailures());
        self::assertNull($p->getEnforcingConsecutive5xx());
        self::assertNull($p->getMaxEjectionPercent());
        self::assertNull($p->getBaseEjectionTime());
    }

    public function testConstructorWithParams(): void
    {
        $p = new PassiveHealthCheck(
            Interval: '10s',
            MaxFailures: 3,
            EnforcingConsecutive5xx: 100,
            MaxEjectionPercent: 50,
        );
        self::assertSame(3, $p->getMaxFailures());
        self::assertSame(100, $p->getEnforcingConsecutive5xx());
        self::assertSame(50, $p->getMaxEjectionPercent());
    }

    public function testFluentSetters(): void
    {
        $p = new PassiveHealthCheck();
        $result = $p->setMaxFailures(5)
            ->setEnforcingConsecutive5xx(80)
            ->setMaxEjectionPercent(25);
        self::assertSame($p, $result);
        self::assertSame(5, $p->getMaxFailures());
        self::assertSame(80, $p->getEnforcingConsecutive5xx());
        self::assertSame(25, $p->getMaxEjectionPercent());
    }

    public function testJsonUnserializeIntegerNanoseconds(): void
    {
        $p = PassiveHealthCheck::jsonUnserialize(decoded: json_decode('{"Interval":10000000000,"MaxFailures":3,"BaseEjectionTime":30000000000}', false));
        self::assertSame(10000000000, $p->getInterval()->Nanoseconds());
        self::assertSame(30000000000, $p->getBaseEjectionTime()->Nanoseconds());
        self::assertSame(3, $p->getMaxFailures());
    }

    public function testJsonUnserializeDurationStrings(): void
    {
        $p = PassiveHealthCheck::jsonUnserialize(decoded: json_decode('{"Interval":"10s","base_ejection_time":"30s"}', false));
        self::assertSame(10000000000, $p->getInterval()->Nanoseconds());
        self::assertSame(30000000000, $p->getBaseEjectionTime()->Nanoseconds());
    }

    public function testJsonRoundTrip(): void
    {
        $p = new PassiveHealthCheck(Interval: '10s', MaxFailures: 2, BaseEjectionTime: '30s');
        $p2 = PassiveHealthCheck::jsonUnserialize(decoded: json_decode(json_encode($p), false));
        self::assertSame(10000000000, $p2->getInterval()->Nanoseconds());
        self::assertSame(30000000000, $p2->getBaseEjectionTime()->Nanoseconds());
        self::assertSame(2, $p2->getMaxFailures());
    }

}
