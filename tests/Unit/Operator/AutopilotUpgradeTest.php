<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\AutopilotUpgrade;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AutopilotUpgradeTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $u = new AutopilotUpgrade();
        self::assertSame('', $u->getStatus());
        self::assertSame('', $u->getTargetVersion());
        self::assertSame([], $u->getTargetVersionVoters());
        self::assertSame([], $u->getTargetVersionNonVoters());
        self::assertSame([], $u->getTargetVersionReadReplicas());
        self::assertSame([], $u->getOtherVersionVoters());
        self::assertSame([], $u->getOtherVersionNonVoters());
        self::assertSame([], $u->getOtherVersionReadReplicas());
        self::assertSame([], $u->getRedundancyZones());
    }

    public function testConstructorWithValues(): void
    {
        $u = new AutopilotUpgrade(
            Status: 'awaiting-new-voters',
            TargetVersion: '1.22.0',
            TargetVersionVoters: ['a'],
            OtherVersionVoters: ['b'],
        );
        self::assertSame('awaiting-new-voters', $u->getStatus());
        self::assertSame('1.22.0', $u->getTargetVersion());
        self::assertSame(['a'], $u->getTargetVersionVoters());
        self::assertSame(['b'], $u->getOtherVersionVoters());
    }

    public function testFluentSetters(): void
    {
        $u = new AutopilotUpgrade();
        $result = $u
            ->setStatus('idle')
            ->setTargetVersion('1.0')
            ->setTargetVersionVoters('v1')
            ->setTargetVersionNonVoters('nv1')
            ->setTargetVersionReadReplicas('rr1')
            ->setOtherVersionVoters('ov1')
            ->setOtherVersionNonVoters('onv1')
            ->setOtherVersionReadReplicas('orr1')
            ->setRedundancyZones([]);
        self::assertSame($u, $result);
    }

    public function testJsonSerialize(): void
    {
        $u = new AutopilotUpgrade(Status: 'idle', TargetVersion: '1.0', TargetVersionVoters: ['a']);
        $out = $u->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertSame('idle', $out->Status);
        self::assertSame('1.0', $out->TargetVersion);
        self::assertSame(['a'], $out->TargetVersionVoters);
    }

    public function testJsonSerializeOmitsEmpty(): void
    {
        $u = new AutopilotUpgrade(Status: 'idle');
        $out = $u->jsonSerialize();
        self::assertObjectNotHasProperty('TargetVersion', $out);
        self::assertObjectNotHasProperty('TargetVersionVoters', $out);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->Status = 'idle';
        $decoded->TargetVersion = '1.22.0';
        $decoded->TargetVersionVoters = ['x'];
        $u = AutopilotUpgrade::jsonUnserialize($decoded);
        self::assertSame('idle', $u->getStatus());
        self::assertSame('1.22.0', $u->getTargetVersion());
    }
}

