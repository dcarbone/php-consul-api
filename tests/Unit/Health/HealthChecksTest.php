<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Health;

use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class HealthChecksTest extends TestCase
{
    public function testConstructorEmpty(): void
    {
        $hcs = new HealthChecks();
        self::assertCount(0, $hcs);
    }

    public function testConstructorWithChecks(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Name: 'c1', Status: 'passing'),
            new HealthCheck(Name: 'c2', Status: 'critical'),
        );
        self::assertCount(2, $hcs);
    }

    public function testCountable(): void
    {
        $hcs = new HealthChecks(new HealthCheck());
        self::assertSame(1, $hcs->count());
    }

    public function testIteratorAggregate(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Name: 'a'),
            new HealthCheck(Name: 'b'),
        );
        $names = [];
        foreach ($hcs as $hc) {
            $names[] = $hc->getName();
        }
        self::assertSame(['a', 'b'], $names);
    }

    public function testArrayAccess(): void
    {
        $hcs = new HealthChecks(new HealthCheck(Name: 'x'));
        self::assertTrue(isset($hcs[0]));
        self::assertFalse(isset($hcs[1]));
        self::assertSame('x', $hcs[0]->getName());
    }

    public function testAggregatedStatusAllPassing(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Status: 'passing'),
            new HealthCheck(Status: 'passing'),
        );
        self::assertSame('passing', $hcs->AggregatedStatus());
    }

    public function testAggregatedStatusCritical(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Status: 'passing'),
            new HealthCheck(Status: 'critical'),
        );
        self::assertSame('critical', $hcs->AggregatedStatus());
    }

    public function testAggregatedStatusWarning(): void
    {
        $hcs = new HealthChecks(
            new HealthCheck(Status: 'passing'),
            new HealthCheck(Status: 'warning'),
        );
        self::assertSame('warning', $hcs->AggregatedStatus());
    }

    public function testAggregatedStatusEmpty(): void
    {
        $hcs = new HealthChecks();
        self::assertSame('passing', $hcs->AggregatedStatus());
    }

    public function testJsonSerialize(): void
    {
        $hcs = new HealthChecks(new HealthCheck(Name: 'test'));
        $out = $hcs->jsonSerialize();
        self::assertIsArray($out);
        self::assertCount(1, $out);
    }
}

