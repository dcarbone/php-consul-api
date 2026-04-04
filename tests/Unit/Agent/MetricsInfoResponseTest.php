<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\MetricsInfo;
use DCarbone\PHPConsulAPI\Agent\MetricsInfoResponse;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MetricsInfoResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $r = new MetricsInfoResponse();
        self::assertNull($r->getValue());
        self::assertNull($r->MetricsInfo);
        self::assertNull($r->Err);
    }

    public function testUnmarshalValueNull(): void
    {
        $r = new MetricsInfoResponse();
        $r->unmarshalValue(null);
        self::assertNull($r->getValue());
    }

    public function testUnmarshalValueWithMetrics(): void
    {
        $obj = new \stdClass();
        $obj->Timestamp = '2025-01-01T00:00:00Z';
        $obj->Gauges = [];
        $obj->Points = [];
        $obj->Counters = [];
        $obj->Samples = [];

        $r = new MetricsInfoResponse();
        $r->unmarshalValue($obj);

        $info = $r->getValue();
        self::assertInstanceOf(MetricsInfo::class, $info);
        self::assertSame('2025-01-01T00:00:00Z', $info->getTimestamp());
    }

    public function testOffsetAccess(): void
    {
        $r = new MetricsInfoResponse();
        self::assertTrue(isset($r[0]));
        self::assertTrue(isset($r[1]));
        self::assertNull($r[0]);
        self::assertNull($r[1]);
    }
}

