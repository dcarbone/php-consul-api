<?php

namespace DCarbone\PHPConsulAPITests\Integration\Health;

use DCarbone\PHPConsulAPI\Agent\AgentClient;
use DCarbone\PHPConsulAPI\Agent\AgentServiceCheck;
use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Health\HealthClient;
use DCarbone\PHPConsulAPI\Health\ServiceEntry;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractIntegrationTestCase;

final class HealthClientTest extends AbstractIntegrationTestCase
{
    protected bool $singlePerTest = true;

    private const ServiceID = 'health-test-service-id';
    private const ServiceName = 'health-test-service';
    private const CheckID = 'health-test-check';

    public function testCanConstructClient(): void
    {
        $client = new HealthClient(ConsulManager::testConfig());
        self::assertInstanceOf(HealthClient::class, $client);
    }

    public function testCanConstructViaConsul(): void
    {
        $consul = new Consul(ConsulManager::testConfig());
        self::assertInstanceOf(HealthClient::class, $consul->Health);
        self::assertInstanceOf(HealthClient::class, $consul->Health());
    }

    public function testCanReadNodeAndServiceHealth(): void
    {
        $agentClient = new AgentClient(ConsulManager::testConfig());
        $healthClient = new HealthClient(ConsulManager::testConfig());

        [$nodeName, $err] = $agentClient->NodeName();
        self::assertNull($err);
        self::assertNotSame('', $nodeName);

        [$checks, $qm, $err] = $healthClient->Node($nodeName);
        self::assertNull($err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertInstanceOf(HealthChecks::class, $checks);
        self::assertGreaterThanOrEqual(1, count($checks));

        $registration = new AgentServiceRegistration(
            ID: self::ServiceID,
            Name: self::ServiceName,
            Tags: ['blue', 'v1'],
            Address: '127.0.0.1',
            Port: 12345,
            Check: new AgentServiceCheck(
                CheckID: self::CheckID,
                TTL: '30s',
            ),
        );

        $err = $agentClient->ServiceRegister($registration);
        self::assertNull($err);

        $err = $agentClient->PassTTL(self::CheckID, 'passing');
        self::assertNull($err);

        [$serviceChecks, $qm, $err] = $healthClient->Checks(self::ServiceName);
        self::assertNull($err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertInstanceOf(HealthChecks::class, $serviceChecks);
        self::assertNotCount(0, $serviceChecks);

        $matchedCheck = null;
        foreach ($serviceChecks as $check) {
            if (self::CheckID === $check->CheckID) {
                $matchedCheck = $check;
                break;
            }
        }

        self::assertInstanceOf(HealthCheck::class, $matchedCheck);
        self::assertSame(self::ServiceID, $matchedCheck->ServiceID);
        self::assertSame(self::ServiceName, $matchedCheck->ServiceName);

        [$entries, $qm, $err] = $healthClient->ServiceMultipleTags(self::ServiceName, ['blue'], true);
        self::assertNull($err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertCount(1, $entries);
        self::assertInstanceOf(ServiceEntry::class, $entries[0]);
        self::assertSame(self::ServiceID, $entries[0]->Service?->ID);

        [$passingChecks, $qm, $err] = $healthClient->State(Consul::HealthPassing);
        self::assertNull($err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertInstanceOf(HealthChecks::class, $passingChecks);

        $found = false;
        foreach ($passingChecks as $check) {
            if (self::CheckID === $check->CheckID) {
                $found = true;
                break;
            }
        }
        self::assertTrue($found);

        $err = $agentClient->ServiceDeregister(self::ServiceID);
        self::assertNull($err);
    }

    public function testStateRejectsUnknown(): void
    {
        $client = new HealthClient(ConsulManager::testConfig());

        $response = $client->State('unknown');

        self::assertNotNull($response->Err);
    }
}
