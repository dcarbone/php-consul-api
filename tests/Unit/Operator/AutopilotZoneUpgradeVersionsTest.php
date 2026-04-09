<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\AutopilotZoneUpgradeVersions;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AutopilotZoneUpgradeVersionsTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $z = new AutopilotZoneUpgradeVersions();
        self::assertSame([], $z->getTargetVersionVoters());
        self::assertSame([], $z->TargetVersionVoters);
        self::assertSame([], $z->getTargetVersionNonVoters());
        self::assertSame([], $z->TargetVersionNonVoters);
        self::assertSame([], $z->getOtherVersionVoters());
        self::assertSame([], $z->OtherVersionVoters);
        self::assertSame([], $z->getOtherVersionNonVoters());
        self::assertSame([], $z->OtherVersionNonVoters);
    }

    public function testConstructorWithValues(): void
    {
        $z = new AutopilotZoneUpgradeVersions(
            TargetVersionVoters: ['a', 'b'],
            TargetVersionNonVoters: ['c'],
            OtherVersionVoters: ['d'],
            OtherVersionNonVoters: ['e', 'f'],
        );
        self::assertSame(['a', 'b'], $z->getTargetVersionVoters());
        self::assertSame(['a', 'b'], $z->TargetVersionVoters);
        self::assertSame(['c'], $z->getTargetVersionNonVoters());
        self::assertSame(['c'], $z->TargetVersionNonVoters);
        self::assertSame(['d'], $z->getOtherVersionVoters());
        self::assertSame(['d'], $z->OtherVersionVoters);
        self::assertSame(['e', 'f'], $z->getOtherVersionNonVoters());
        self::assertSame(['e', 'f'], $z->OtherVersionNonVoters);
    }

    public function testFluentSetters(): void
    {
        $z = new AutopilotZoneUpgradeVersions();
        $result = $z
            ->setTargetVersionVoters('x')
            ->setTargetVersionNonVoters('y')
            ->setOtherVersionVoters('z')
            ->setOtherVersionNonVoters('w');
        self::assertSame($z, $result);
        self::assertSame(['x'], $z->getTargetVersionVoters());
        self::assertSame(['x'], $z->TargetVersionVoters);
        self::assertSame(['y'], $z->TargetVersionNonVoters);
        self::assertSame(['z'], $z->OtherVersionVoters);
        self::assertSame(['w'], $z->OtherVersionNonVoters);
    }

    public function testJsonSerializeOmitsEmptyArrays(): void
    {
        $z = new AutopilotZoneUpgradeVersions();
        $out = $z->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertObjectNotHasProperty('TargetVersionVoters', $out);
    }

    public function testJsonSerializeIncludesNonEmpty(): void
    {
        $z = new AutopilotZoneUpgradeVersions(TargetVersionVoters: ['a']);
        $out = $z->jsonSerialize();
        self::assertSame(['a'], $out->TargetVersionVoters);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->TargetVersionVoters = ['a', 'b'];
        $decoded->TargetVersionNonVoters = ['c'];
        $decoded->OtherVersionVoters = [];
        $decoded->OtherVersionNonVoters = [];
        $z = AutopilotZoneUpgradeVersions::jsonUnserialize($decoded);
        self::assertSame(['a', 'b'], $z->getTargetVersionVoters());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new AutopilotZoneUpgradeVersions(TargetVersionVoters: ['v1']);
        $json = json_encode($original);
        $restored = AutopilotZoneUpgradeVersions::jsonUnserialize(json_decode($json, false));
        self::assertSame(['v1'], $restored->getTargetVersionVoters());
    }
}

