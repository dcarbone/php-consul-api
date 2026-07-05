<?php

namespace DCarbone\PHPConsulAPITests\Unit\Operator;

use DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AutopilotConfigurationTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $c = new AutopilotConfiguration();
        self::assertFalse($c->isCleanupDeadServers());
        self::assertFalse($c->CleanupDeadServers);
        self::assertNull($c->getLastContactThreshold());
        self::assertNull($c->LastContactThreshold);
        self::assertSame(0, $c->getMaxTrailingLogs());
        self::assertSame(0, $c->MaxTrailingLogs);
        self::assertSame(0, $c->getMinQuorum());
        self::assertSame(0, $c->MinQuorum);
        self::assertNull($c->getServerStabilizationTime());
        self::assertNull($c->ServerStabilizationTime);
        self::assertSame('', $c->getRedundancyZoneTag());
        self::assertSame('', $c->RedundancyZoneTag);
        self::assertFalse($c->isDisableUpgradeMigration());
        self::assertFalse($c->DisableUpgradeMigration);
        self::assertSame('', $c->getUpgradeVersionTag());
        self::assertSame('', $c->UpgradeVersionTag);
        self::assertSame(0, $c->getCreateIndex());
        self::assertSame(0, $c->CreateIndex);
        self::assertSame(0, $c->getModifyIndex());
        self::assertSame(0, $c->ModifyIndex);
    }

    public function testConstructorWithValues(): void
    {
        $c = new AutopilotConfiguration(
            CleanupDeadServers: true,
            LastContactThreshold: '200ms',
            MaxTrailingLogs: 250,
            MinQuorum: 3,
            ServerStabilizationTime: '10s',
            RedundancyZoneTag: 'zone',
            DisableUpgradeMigration: true,
            UpgradeVersionTag: 'build',
            CreateIndex: 5,
            ModifyIndex: 10,
        );
        self::assertTrue($c->isCleanupDeadServers());
        self::assertTrue($c->CleanupDeadServers);
        self::assertNotNull($c->getLastContactThreshold());
        self::assertNotNull($c->LastContactThreshold);
        self::assertSame(250, $c->getMaxTrailingLogs());
        self::assertSame(250, $c->MaxTrailingLogs);
        self::assertSame(3, $c->getMinQuorum());
        self::assertSame(3, $c->MinQuorum);
        self::assertNotNull($c->getServerStabilizationTime());
        self::assertNotNull($c->ServerStabilizationTime);
        self::assertSame('zone', $c->getRedundancyZoneTag());
        self::assertSame('zone', $c->RedundancyZoneTag);
        self::assertTrue($c->isDisableUpgradeMigration());
        self::assertTrue($c->DisableUpgradeMigration);
        self::assertSame('build', $c->getUpgradeVersionTag());
        self::assertSame('build', $c->UpgradeVersionTag);
        self::assertSame(5, $c->getCreateIndex());
        self::assertSame(5, $c->CreateIndex);
        self::assertSame(10, $c->getModifyIndex());
        self::assertSame(10, $c->ModifyIndex);
    }

    public function testFluentSetters(): void
    {
        $c = new AutopilotConfiguration();
        $result = $c
            ->setCleanupDeadServers(true)
            ->setLastContactThreshold('100ms')
            ->setMaxTrailingLogs(100)
            ->setMinQuorum(1)
            ->setServerStabilizationTime('5s')
            ->setRedundancyZoneTag('rz')
            ->setDisableUpgradeMigration(true)
            ->setUpgradeVersionTag('uv')
            ->setCreateIndex(1)
            ->setModifyIndex(2);
        self::assertSame($c, $result);
        self::assertTrue($c->CleanupDeadServers);
        self::assertSame(100, $c->MaxTrailingLogs);
        self::assertSame(1, $c->MinQuorum);
        self::assertSame('rz', $c->RedundancyZoneTag);
        self::assertTrue($c->DisableUpgradeMigration);
        self::assertSame('uv', $c->UpgradeVersionTag);
        self::assertSame(1, $c->CreateIndex);
        self::assertSame(2, $c->ModifyIndex);
    }

    public function testNullDurationSetters(): void
    {
        $c = new AutopilotConfiguration(LastContactThreshold: '5s', ServerStabilizationTime: '10s');
        self::assertNotNull($c->getLastContactThreshold());
        self::assertNotNull($c->getServerStabilizationTime());
        $c->setLastContactThreshold(null);
        $c->setServerStabilizationTime(null);
        self::assertNull($c->getLastContactThreshold());
        self::assertNull($c->getServerStabilizationTime());
    }

    public function testJsonSerialize(): void
    {
        $c = new AutopilotConfiguration(CleanupDeadServers: true, MaxTrailingLogs: 100);
        $out = $c->jsonSerialize();
        self::assertInstanceOf(\stdClass::class, $out);
        self::assertTrue($out->CleanupDeadServers);
        self::assertSame(100, $out->MaxTrailingLogs);
    }

    public function testJsonSerializeOmitsEmptyOptionals(): void
    {
        $c = new AutopilotConfiguration();
        $out = $c->jsonSerialize();
        self::assertObjectNotHasProperty('RedundancyZoneTag', $out);
        self::assertObjectNotHasProperty('UpgradeVersionTag', $out);
        self::assertObjectNotHasProperty('LastContactThreshold', $out);
        self::assertObjectNotHasProperty('ServerStabilizationTime', $out);
    }

    public function testJsonUnserialize(): void
    {
        $decoded = new \stdClass();
        $decoded->CleanupDeadServers = true;
        $decoded->LastContactThreshold = '200ms';
        $decoded->MaxTrailingLogs = 250;
        $decoded->MinQuorum = 3;
        $decoded->ServerStabilizationTime = '10s';
        $decoded->RedundancyZoneTag = 'zone';
        $decoded->DisableUpgradeMigration = false;
        $decoded->UpgradeVersionTag = '';
        $decoded->CreateIndex = 5;
        $decoded->ModifyIndex = 10;

        $c = AutopilotConfiguration::jsonUnserialize($decoded);
        self::assertTrue($c->isCleanupDeadServers());
        self::assertNotNull($c->getLastContactThreshold());
        self::assertSame(250, $c->getMaxTrailingLogs());
        self::assertSame(3, $c->getMinQuorum());
        self::assertNotNull($c->getServerStabilizationTime());
        self::assertSame(5, $c->getCreateIndex());
    }

    public function testJsonRoundTrip(): void
    {
        $original = new AutopilotConfiguration(
            CleanupDeadServers: true,
            MaxTrailingLogs: 200,
            CreateIndex: 7,
            ModifyIndex: 14,
        );
        $json = json_encode($original);
        $restored = AutopilotConfiguration::jsonUnserialize(json_decode($json, false));
        self::assertSame($original->isCleanupDeadServers(), $restored->isCleanupDeadServers());
        self::assertSame($original->getMaxTrailingLogs(), $restored->getMaxTrailingLogs());
        self::assertSame($original->getCreateIndex(), $restored->getCreateIndex());
        self::assertSame($original->getModifyIndex(), $restored->getModifyIndex());
    }
}

