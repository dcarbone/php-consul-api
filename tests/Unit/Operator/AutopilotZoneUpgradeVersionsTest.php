<?php

declare(strict_types=1);

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
        self::assertSame([], $z->getTargetVersionNonVoters());
        self::assertSame([], $z->getOtherVersionVoters());
        self::assertSame([], $z->getOtherVersionNonVoters());
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
        self::assertSame(['c'], $z->getTargetVersionNonVoters());
        self::assertSame(['d'], $z->getOtherVersionVoters());
        self::assertSame(['e', 'f'], $z->getOtherVersionNonVoters());
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

