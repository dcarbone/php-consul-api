<?php

namespace DCarbone\PHPConsulAPITests\Unit\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentServiceChecksInfo;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AgentServiceChecksInfoTest extends TestCase
{
    public function testConstructorDefaults(): void
    {
        $i = new AgentServiceChecksInfo();
        self::assertSame('', $i->getAggregatedStatus());
        self::assertNull($i->getService());
        self::assertInstanceOf(HealthChecks::class, $i->getChecks());
    }

    public function testConstructorWithParams(): void
    {
        $svc = new AgentService(ID: 'svc-1', Service: 'web');
        $i = new AgentServiceChecksInfo(AggregatedStatus: 'passing', Service: $svc);
        self::assertSame('passing', $i->getAggregatedStatus());
        self::assertSame($svc, $i->getService());
    }

    public function testFluentSetters(): void
    {
        $i = new AgentServiceChecksInfo();
        $svc = new AgentService(ID: 's');
        $result = $i->setAggregatedStatus('critical')->setService($svc);
        self::assertSame($i, $result);
        self::assertSame('critical', $i->getAggregatedStatus());
        self::assertSame($svc, $i->getService());
    }
}

