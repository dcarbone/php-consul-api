<?php

namespace DCarbone\PHPConsulAPITests\Usage\Agent;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Agent\AgentMember;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Agent\AgentServiceCheck;
use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;
use PHPUnit\Framework\AssertionFailedError;

/**
 * Class AgentClientTest
 *
 * @internal
 */
final class AgentClientTest extends AbstractUsageTests
{
    public const Service1Name = 'test_1_service';
    public const Service2Name = 'test_2_service';
    /** @var bool */
    protected static $singlePerClass = true;

    public function testCanConstructAgentClient(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        self::assertInstanceOf(AgentClient::class, $client);
    }

    /**
     * @depends testCanConstructAgentClient
     */
    public function testCanGetSelf(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$self, $err] = $client->Self();
        self::assertNull($err);
        self::assertIsArray(
            $self,
            \sprintf(
                'Expected AgentClient::self to return array, saw "%s"',
                \gettype($self)
            )
        );
    }

    /**
     * @depends testCanGetSelf
     */
    public function testCanReloadSelf(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        $err    = $client->Reload();
        self::assertNull($err, \sprintf('AgentClient::reload returned error: %s', $err));
    }

    /**
     * @depends testCanGetSelf
     */
    public function testCanGetNodeName(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$nodeName, $err] = $client->NodeName();
        self::assertNull($err, \sprintf('Unable to get agent node name: %s', $err));
        self::assertIsString(
            $nodeName,
            \sprintf('node name expected to be string, %s seen', \gettype($nodeName))
        );
        self::assertNotEmpty($nodeName, 'NodeName was empty!');
    }

    /**
     * @depends testCanConstructAgentClient
     */
    public function testCanGeMembers(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$members, $err] = $client->Members();
        self::assertNull($err, \sprintf('AgentClient::members returned error: %s', $err));
        self::assertIsArray($members);
        self::assertContainsOnlyInstancesOf(AgentMember::class, $members);
        self::assertCount(1, $members);
    }

    /**
     * @depends testCanConstructAgentClient
     */
    public function testCanRegisterServiceNoChecks(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $svc = new AgentServiceRegistration();
        $svc
            ->setName(self::Service1Name)
            ->setAddress('127.0.0.1')
            ->setPort(1234);

        $err = $client->ServiceRegister($svc);
        self::assertNull($err, \sprintf('AgentClient::serviceRegister returned error: %s', $err));
    }

    /**
     * @depends testCanConstructAgentClient
     */
    public function testCanRegisterServiceWithOneCheck(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $svc = new AgentServiceRegistration();
        $svc
            ->setName(self::Service2Name)
            ->setAddress('127.0.0.1')
            ->setPort(4321)
            ->setCheck(new AgentServiceCheck([
                'TTL' => '5s',
            ]));

        $err = $client->ServiceRegister($svc);
        self::assertNull($err, \sprintf('AgentClient::serviceRegister returned error: %s', $err));
    }

    /**
     * TODO: Expand later with multi-service return test?
     *
     * @depends testCanRegisterServiceNoChecks
     */
    public function testCanGetServiceList(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        [$svcs, $err] = $client->Services();

        try {
            self::assertNull($err, \sprintf('AgentClient::services return error: %s', $err));
            self::assertIsArray($svcs);
            self::assertContainsOnlyInstancesOf(AgentService::class, $svcs);

            // NOTE: will always contain "consul" service
            self::assertCount(2, $svcs);
        } catch (AssertionFailedError $e) {
            echo "\nservices list:\n";
            \var_dump($svcs);
            echo "\n";

            throw $e;
        }
    }

    /**
     * @depends testCanRegisterServiceNoChecks
     */
    public function testCanDeregisterService(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $err = $client->ServiceDeregister(self::Service1Name);
        self::assertNull($err, \sprintf('AgentClient::serviceDeregister returned error: %s', $err));

        [$svcs, $err] = $client->Services();

        try {
            self::assertNull($err, \sprintf('AgentClient::services returned error: %s', $err));
            self::assertIsArray($svcs);
            self::assertContainsOnlyInstancesOf(AgentService::class, $svcs);
            self::assertCount(1, $svcs);
        } catch (AssertionFailedError $e) {
            echo "\nservices list:\n";
            \var_dump($svcs);
            echo "\n";

            throw $e;
        }
    }

    /**
     * @depends testCanDeregisterService
     */
    public function testCanRegisterServiceWithCheck(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());

        $svc = new AgentServiceRegistration();
        $svc
            ->setName(self::Service1Name)
            ->setPort(1234)
            ->setAddress('127.0.0.1')
            ->setCheck(new AgentServiceCheck([
                'TCP'      => '127.0.0.1',
                'Interval' => '30s',
            ]));

        $err = $client->ServiceRegister($svc);
        self::assertNull($err, \sprintf('Error registering service with check: %s', $err));

        \sleep(2);

        [$svcs, $err] = $client->Services();

        try {
            self::assertNull($err, \sprintf('AgentClient::services returned error: %s', $err));
            self::assertIsArray($svcs);
            self::assertContainsOnlyInstancesOf(AgentService::class, $svcs);
            self::assertCount(2, $svcs);
        } catch (AssertionFailedError $e) {
            echo "\nservices list:\n";
            \var_dump($svcs);
            echo "\n";

            throw $e;
        }

        $err = $client->ServiceDeregister(self::Service1Name);
        self::assertNull($err, \sprintf('Error deregistering service: %s', $err));
    }
}
