<?php

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
        self::assertFalse($s->Healthy);
        self::assertSame(0, $s->getFailureTolerance());
        self::assertSame(0, $s->FailureTolerance);
        self::assertSame(0, $s->getOptimisticFailureTolerance());
        self::assertSame(0, $s->OptimisticFailureTolerance);
        self::assertSame([], $s->getServers());
        self::assertSame([], $s->Servers);
        self::assertSame('', $s->getLeader());
        self::assertSame('', $s->Leader);
        self::assertSame([], $s->getVoters());
        self::assertSame([], $s->Voters);
        self::assertSame([], $s->getReadReplicas());
        self::assertSame([], $s->ReadReplicas);
        self::assertSame([], $s->getRedundancyZone());
        self::assertSame([], $s->RedundancyZone);
        self::assertNull($s->getUpgrade());
        self::assertNull($s->Upgrade);
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
        self::assertTrue($s->Healthy);
        self::assertSame(1, $s->getFailureTolerance());
        self::assertSame(1, $s->FailureTolerance);
        self::assertSame('leader-id', $s->getLeader());
        self::assertSame('leader-id', $s->Leader);
        self::assertSame(['v1', 'v2'], $s->getVoters());
        self::assertSame(['v1', 'v2'], $s->Voters);
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
        self::assertTrue($s->Healthy);
        self::assertSame(1, $s->FailureTolerance);
        self::assertSame(2, $s->OptimisticFailureTolerance);
        self::assertSame('l', $s->Leader);
        self::assertSame(['v1'], $s->Voters);
        self::assertSame(['r1'], $s->ReadReplicas);
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

