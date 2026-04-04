<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\AutopilotState;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AutopilotStateTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $s = new AutopilotState();
        self::assertFalse($s->isHealthy());
        self::assertSame(0, $s->getFailureTolerance());
        self::assertSame(0, $s->getOptimisticFailureTolerance());
        self::assertSame([], $s->getServers());
        self::assertSame('', $s->getLeader());
        self::assertSame([], $s->getVoters());
        self::assertSame([], $s->getReadReplicas());
        self::assertSame([], $s->getRedundancyZone());
        self::assertNull($s->getUpgrade());
    }

    public function testConstructorWithValues(): void
    {
        $s = new AutopilotState(
            Healthy: true,
            FailureTolerance: 1,
            OptimisticFailureTolerance: 2,
            Leader: 'leader-id',
            Voters: ['v1', 'v2'],
        );
        self::assertTrue($s->isHealthy());
        self::assertSame(1, $s->getFailureTolerance());
        self::assertSame('leader-id', $s->getLeader());
        self::assertSame(['v1', 'v2'], $s->getVoters());
    }

    public function testFluentSetters(): void
    {
        $s = new AutopilotState();
        $result = $s
            ->setHealthy(true)
            ->setFailureTolerance(1)
            ->setOptimisticFailureTolerance(2)
            ->setServers([])
            ->setLeader('l')
            ->setVoters('v1')
            ->setReadReplicas('r1')
            ->setRedundancyZone([])
            ->setUpgrade(null);
        self::assertSame($s, $result);
    }

    public function testJsonSerialize(): void
    {
        $s = new AutopilotState(Healthy: true, FailureTolerance: 1, Leader: 'l');
        $out = $s->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertTrue($out->Healthy);
        self::assertSame(1, $out->FailureTolerance);
        self::assertSame('l', $out->Leader);
    }

    public function testJsonSerializeOmitsEmptyOptionals(): void
    {
        $s = new AutopilotState();
        $out = $s->jsonSerialize();
        self::assertObjectNotHasProperty('ReadReplicas', $out);
        self::assertObjectNotHasProperty('RedundancyZone', $out);
        self::assertObjectNotHasProperty('Upgrade', $out);
    }
}

