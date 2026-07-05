<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\AutopilotZone;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AutopilotZoneTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $zone = new AutopilotZone();
        self::assertSame([], $zone->getServers());
        self::assertSame([], $zone->Servers);
        self::assertSame([], $zone->getVoters());
        self::assertSame([], $zone->Voters);
        self::assertSame(0, $zone->getFailureTolerance());
        self::assertSame(0, $zone->FailureTolerance);
    }

    public function testConstructorWithValues(): void
    {
        $zone = new AutopilotZone(
            Servers: ['srv-1', 'srv-2'],
            Voters: ['v-1'],
            FailureTolerance: 2,
        );
        self::assertSame(['srv-1', 'srv-2'], $zone->getServers());
        self::assertSame(['srv-1', 'srv-2'], $zone->Servers);
        self::assertSame(['v-1'], $zone->getVoters());
        self::assertSame(['v-1'], $zone->Voters);
        self::assertSame(2, $zone->getFailureTolerance());
        self::assertSame(2, $zone->FailureTolerance);
    }

    public function testVariadicSetServers(): void
    {
        $zone = new AutopilotZone();
        $result = $zone->setServers('a', 'b', 'c');

        self::assertSame($zone, $result);
        self::assertSame(['a', 'b', 'c'], $zone->getServers());
    }

    public function testVariadicSetVoters(): void
    {
        $zone = new AutopilotZone();
        $result = $zone->setVoters('x', 'y');

        self::assertSame($zone, $result);
        self::assertSame(['x', 'y'], $zone->getVoters());
    }

    public function testVariadicSettersReplaceExisting(): void
    {
        $zone = new AutopilotZone(Servers: ['old'], Voters: ['old-v']);

        $zone->setServers('new-1');
        $zone->setVoters('new-v-1', 'new-v-2');

        self::assertSame(['new-1'], $zone->getServers());
        self::assertSame(['new-v-1', 'new-v-2'], $zone->getVoters());
    }

    public function testFluentSetters(): void
    {
        $zone = new AutopilotZone();
        $result = $zone
            ->setServers('s1')
            ->setVoters('v1')
            ->setFailureTolerance(3);

        self::assertSame($zone, $result);
        self::assertSame(3, $zone->getFailureTolerance());
    }

    public function testJsonSerialize(): void
    {
        $zone = new AutopilotZone(
            Servers: ['srv-a'],
            Voters: ['vtr-a'],
            FailureTolerance: 1,
        );

        $out = $zone->jsonSerialize();

        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame(['srv-a'], $out->Servers);
        self::assertSame(['vtr-a'], $out->Voters);
        self::assertSame(1, $out->FailureTolerance);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->Servers = ['s1', 's2'];
        $decoded->Voters = ['v1'];
        $decoded->FailureTolerance = 5;

        $zone = AutopilotZone::jsonUnserialize($decoded);

        self::assertSame(['s1', 's2'], $zone->getServers());
        self::assertSame(['v1'], $zone->getVoters());
        self::assertSame(5, $zone->getFailureTolerance());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new AutopilotZone(
            Servers: ['srv-1', 'srv-2'],
            Voters: ['v-1'],
            FailureTolerance: 2,
        );

        $restored = AutopilotZone::jsonUnserialize($original->jsonSerialize());

        self::assertSame($original->getServers(), $restored->getServers());
        self::assertSame($original->getVoters(), $restored->getVoters());
        self::assertSame($original->getFailureTolerance(), $restored->getFailureTolerance());
    }
}

