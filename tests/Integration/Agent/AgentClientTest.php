<?php

namespace DCarbone\PHPConsulAPITests\Integration\Agent;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
 */

use DCarbone\PHPConsulAPI\Agent\AgentClient;
use DCarbone\PHPConsulAPI\Agent\AgentCheck;
use DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration;
use DCarbone\PHPConsulAPI\Agent\AgentMember;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Agent\AgentServiceCheck;
use DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig;
use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;
use DCarbone\PHPConsulAPI\Agent\MetricsInfo;
use DCarbone\PHPConsulAPI\Agent\MembersOpts;
use DCarbone\PHPConsulAPI\Agent\ServiceRegisterOpts;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\PHPLib\Error;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\Depends;

final class AgentClientTest extends AbstractIntegrationTestCase
{
    public const Service1Name = 'test_1_service';
    public const Service2Name = 'test_2_service';
    /** @var bool */
    protected static bool $singlePerClass = true;

    public function testCanConstructAgentClient(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        self::assertInstanceOf(AgentClient::class, $client);
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanGetSelf(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$self, $err] = $client->Self();
        self::assertNull($err);
        self::assertIsArray(
            $self,
            sprintf(
                'Expected AgentClient::self to return array, saw "%s"',
                gettype($self)
            )
        );
    }

    #[Depends('testCanGetSelf')]
    public function testCanReloadSelf(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        $err    = $client->Reload();
        self::assertNull($err, sprintf('AgentClient::reload returned error: %s', $err));
    }

    #[Depends('testCanGetSelf')]
    public function testCanGetNodeName(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$nodeName, $err] = $client->NodeName();
        self::assertNull($err, sprintf('Unable to get agent node name: %s', $err));
        self::assertIsString(
            $nodeName,
            sprintf('node name expected to be string, %s seen', gettype($nodeName))
        );
        self::assertNotEmpty($nodeName, 'NodeName was empty!');
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanGeMembers(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$members, $err] = $client->Members();
        self::assertNull($err, sprintf('AgentClient::members returned error: %s', $err));
        self::assertIsArray($members);
        self::assertContainsOnlyInstancesOf(AgentMember::class, $members);
        self::assertCount(1, $members);
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanRegisterServiceNoChecks(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $svc = new AgentServiceRegistration();
        $svc
            ->setName(self::Service1Name)
            ->setAddress('127.0.0.1')
            ->setPort(1234);

        $err = $client->ServiceRegister($svc);
        self::assertNull($err, sprintf('AgentClient::serviceRegister returned error: %s', $err));
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanRegisterServiceWithOneCheck(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $svc = new AgentServiceRegistration();
        $svc
            ->setName(self::Service2Name)
            ->setAddress('127.0.0.1')
            ->setPort(4321)
            ->setCheck(new AgentServiceCheck(
                TTL: '5s',
            ));

        $err = $client->ServiceRegister($svc);
        self::assertNull($err, sprintf('AgentClient::serviceRegister returned error: %s', $err));
    }

    /**
     * TODO: Expand later with multi-service return test?
     */
    #[Depends('testCanRegisterServiceNoChecks')]
    public function testCanGetServiceList(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$svcs, $err] = $client->Services();

        try {
            self::assertNull($err, sprintf('AgentClient::services return error: %s', $err));
            self::assertIsArray($svcs);
            self::assertContainsOnlyInstancesOf(AgentService::class, $svcs);

            // NOTE: will always contain "consul" service
            self::assertCount(2, $svcs);
        } catch (AssertionFailedError $e) {
            echo "\nservices list:\n";
            var_dump($svcs);
            echo "\n";

            throw $e;
        }
    }

    #[Depends('testCanRegisterServiceNoChecks')]
    public function testCanDeregisterService(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $err = $client->ServiceDeregister(self::Service1Name);
        self::assertNull($err, sprintf('AgentClient::serviceDeregister returned error: %s', $err));

        [$svcs, $err] = $client->Services();

        try {
            self::assertNull($err, sprintf('AgentClient::services returned error: %s', $err));
            self::assertIsArray($svcs);
            self::assertContainsOnlyInstancesOf(AgentService::class, $svcs);
            self::assertCount(1, $svcs);
        } catch (AssertionFailedError $e) {
            echo "\nservices list:\n";
            var_dump($svcs);
            echo "\n";

            throw $e;
        }
    }

    #[Depends('testCanDeregisterService')]
    public function testCanRegisterServiceWithCheck(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $svc = new AgentServiceRegistration();
        $svc
            ->setName(self::Service1Name)
            ->setPort(1234)
            ->setAddress('127.0.0.1')
            ->setCheck(Check: new AgentServiceCheck(
                Interval: '30s',
                TCP: '127.0.0.1',
            ));

        $err = $client->ServiceRegister($svc);
        self::assertNull($err, sprintf('Error registering service with check: %s', $err));

        sleep(2);

        [$svcs, $err] = $client->Services();

        try {
            self::assertNull($err, sprintf('AgentClient::services returned error: %s', $err));
            self::assertIsArray($svcs);
            self::assertContainsOnlyInstancesOf(AgentService::class, $svcs);
            self::assertCount(2, $svcs);
        } catch (AssertionFailedError $e) {
            echo "\nservices list:\n";
            var_dump($svcs);
            echo "\n";

            throw $e;
        }

        $err = $client->ServiceDeregister(self::Service1Name);
        self::assertNull($err, sprintf('Error deregistering service: %s', $err));
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanGetHost(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        [$host, $err] = $client->Host();
        self::assertNull($err, sprintf('AgentClient::host returned error: %s', $err));
        self::assertIsArray($host);
        self::assertNotEmpty($host);
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanGetMetrics(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        [$metrics, $err] = $client->Metrics();
        if (null === $err) {
            self::assertInstanceOf(MetricsInfo::class, $metrics);
            return;
        }

        self::assertStringContainsString(
            'json: unsupported value: NaN',
            (string)$err,
            sprintf('AgentClient::metrics returned unexpected error: %s', $err)
        );
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanGetChecksAndFilteredChecks(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$checks, $err] = $client->Checks();
        self::assertNull($err, sprintf('AgentClient::checks returned error: %s', $err));
        self::assertIsArray($checks);
        self::assertContainsOnlyInstancesOf(AgentCheck::class, $checks);

        [$filtered, $err] = $client->Checks('');
        self::assertNull($err, sprintf('AgentClient::checks with empty filter returned error: %s', $err));
        self::assertIsArray($filtered);
        self::assertContainsOnlyInstancesOf(AgentCheck::class, $filtered);
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanGetServicesWithFilter(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$services, $err] = $client->Services('');
        self::assertNull($err, sprintf('AgentClient::services with empty filter returned error: %s', $err));
        self::assertIsArray($services);
        self::assertContainsOnlyInstancesOf(AgentService::class, $services);
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanGetMemberOpts(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        [$members, $err] = $client->Members(new MembersOpts());
        self::assertNull($err, sprintf('AgentClient::membersOpts returned error: %s', $err));
        self::assertIsArray($members);
        self::assertContainsOnlyInstancesOf(AgentMember::class, $members);
        self::assertCount(1, $members);
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanRegisterServiceWithRegisterOptsAndGetByID(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        $serviceID = 'opts-service-id';

        $service = new AgentServiceRegistration();
        $service
            ->setID($serviceID)
            ->setName('opts-service')
            ->setAddress('127.0.0.1')
            ->setPort(43210)
            ->setCheck(new AgentServiceCheck(TTL: '30s'));

        try {
            $err = $client->ServiceRegister($service, new ServiceRegisterOpts(ReplaceExistingChecks: true));
            self::assertNull($err, sprintf('AgentClient::serviceRegisterOpts returned error: %s', $err));

            [$svc, $err] = $client->Service($serviceID);
            self::assertNull($err, sprintf('AgentClient::service returned error: %s', $err));
            self::assertInstanceOf(AgentService::class, $svc);
            self::assertSame($serviceID, $svc->ID);
        } finally {
            $client->ServiceDeregister($serviceID);
        }
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanRegisterDeregisterCheckAndUpdateTTLStates(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        $checkID = 'integration-check-ttl';

        try {
            $err = $client->CheckRegister(new AgentCheckRegistration(
                ID: $checkID,
                Name: 'integration-check-ttl',
                TTL: '30s',
            ));
            self::assertNull($err, sprintf('AgentClient::checkRegister returned error: %s', $err));

            $err = $client->PassTTL($checkID, 'passing');
            self::assertNull($err, sprintf('AgentClient::passTTL returned error: %s', $err));

            $err = $client->WarnTTL($checkID, 'warning');
            self::assertNull($err, sprintf('AgentClient::warnTTL returned error: %s', $err));

            $err = $client->FailTTL($checkID, 'critical');
            self::assertNull($err, sprintf('AgentClient::failTTL returned error: %s', $err));

            $err = $client->UpdateTTL($checkID, 'invalid', 'not-a-valid-status');
            self::assertInstanceOf(Error::class, $err);
        } finally {
            $client->CheckDeregister($checkID);
        }
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanQueryAgentHealthByIDAndName(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        $serviceID = 'unknown-health-service-id';
        $serviceName = 'unknown-health-service-name';

        [$statusByID, $infoByID, $err] = $client->AgentHealthServiceByID($serviceID);
        self::assertInstanceOf(Error::class, $err);
        self::assertSame(Consul::HealthCritical, $statusByID);
        self::assertNull($infoByID);

        [$statusByName, $infoByName, $err] = $client->AgentHealthServiceByName($serviceName);
        self::assertInstanceOf(Error::class, $err);
        self::assertSame(Consul::HealthCritical, $statusByName);
        self::assertIsArray($infoByName);
        self::assertCount(0, $infoByName);
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanToggleServiceMaintenance(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        $serviceID = 'maint-service-id';

        $service = new AgentServiceRegistration();
        $service
            ->setID($serviceID)
            ->setName('maint-service-name')
            ->setAddress('127.0.0.1')
            ->setPort(43212);

        try {
            $err = $client->ServiceRegister($service);
            self::assertNull($err, sprintf('AgentClient::serviceRegister returned error: %s', $err));

            $err = $client->EnableServiceMaintenance($serviceID, 'integration maintenance');
            self::assertNull($err, sprintf('AgentClient::enableServiceMaintenance returned error: %s', $err));

            $err = $client->DisableServiceMaintenance($serviceID);
            self::assertNull($err, sprintf('AgentClient::disableServiceMaintenance returned error: %s', $err));
        } finally {
            $client->ServiceDeregister($serviceID);
        }
    }

    #[Depends('testCanConstructAgentClient')]
    public function testCanToggleNodeMaintenance(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $err = $client->EnableNodeMaintenance('integration node maintenance');
        self::assertNull($err, sprintf('AgentClient::enableNodeMaintenance returned error: %s', $err));

        $err = $client->DisableNodeMaintenance();
        self::assertNull($err, sprintf('AgentClient::disableNodeMaintenance returned error: %s', $err));
    }

    #[Depends('testCanConstructAgentClient')]
    public function testJoinAndForceLeaveEndpoints(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $err = $client->Join('127.0.0.1');
        self::assertNull($err, sprintf('AgentClient::join returned error: %s', $err));

        $err = $client->ForceLeave('nonexistent-node');
        self::assertInstanceOf(Error::class, $err);

        $err = $client->ForceLeavePrune('nonexistent-node');
        self::assertInstanceOf(Error::class, $err);
    }

    public function testConstructGivenConfigWillUnmarshalConfigValuesSuccessfully(): void
    {
        $config = new AgentServiceConnectProxyConfig(
            Config: [
                'envoy_prometheus_bind_addr' => '0.0.0.0:12345',
                'handshake_timeout_ms' => 10000,
                'local_connect_timeout_ms' => 1000,
                'local_request_timeout_ms' => 0,
                'protocol' => 'http',
            ],
        );

        self::assertEquals([
            'envoy_prometheus_bind_addr' => '0.0.0.0:12345',
            'handshake_timeout_ms' => 10000,
            'local_connect_timeout_ms' => 1000,
            'local_request_timeout_ms' => 0,
            'protocol' => 'http',
        ], $config->Config);
    }

    public function testCanLeaveAgent(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        $err = $client->Leave();
        self::assertNull($err, sprintf('AgentClient::leave returned error: %s', $err));
    }
}
