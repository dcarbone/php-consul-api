<?php namespace DCarbone\PHPConsulAPITests\Usage\Agent;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPITests\ConsulManager;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

/**
 * Class AgentClientUsageTests
 * @package DCarbone\PHPConsulAPITests\Usage\Agent
 */
class AgentClientUsageTests extends TestCase {

    const Service1Name = 'test_1_service';
    const Service2Name = 'test_2_service';

    public static function setUpBeforeClass() {
        ConsulManager::startSingle();
    }

    public static function tearDownAfterClass() {
        ConsulManager::stopSingle();
    }

    public function testCanConstructAgentClient() {
        $client = new AgentClient(new Config());
        $this->assertInstanceOf(AgentClient::class, $client);
    }

    /**
     * @depends testCanConstructAgentClient
     */
    public function testCanGetSelf() {
        $client = new AgentClient(new Config());

        $self = $client->self();
        $this->assertInternalType(
            'array',
            $self,
            sprintf(
                'Expected AgentClient::self to return array, saw "%s"',
                gettype($self)
            )
        );
    }

    /**
     * @depends testCanGetSelf
     */
    public function testCanReloadSelf() {
        $client = new AgentClient(new Config());
        $err = $client->reload();
        $this->assertNull($err, sprintf('AgentClient::reload returned error: %s', $err));
    }

    /**
     * @depends testCanGetSelf
     */
    public function testCanGetNodeName() {
        $client = new AgentClient(new Config());

        list($nodeName, $err) = $client->nodeName();
        $this->assertNull($err, sprintf('Unable to get agent node name: %s', $err));
        $this->assertInternalType('string',
            $nodeName,
            sprintf('node name expected to be string, %s seen', gettype($nodeName)));
        $this->assertNotEmpty($nodeName, 'NodeName was empty!');
    }

    /**
     * @depends testCanConstructAgentClient
     */
    public function testCanGeMembers() {
        $client = new AgentClient(new Config());

        list($members, $err) = $client->members();
        $this->assertNull($err, sprintf('AgentClient::members returned error: %s', $err));
        $this->assertInternalType('array', $members);
        $this->assertContainsOnlyInstancesOf(AgentMember::class, $members);
        $this->assertCount(1, $members);
    }

    /**
     * @depends testCanConstructAgentClient
     */
    public function testCanRegisterServiceNoChecks() {
        $client = new AgentClient(new Config());

        $svc = new AgentServiceRegistration();
        $svc
            ->setName(self::Service1Name)
            ->setAddress('127.0.0.1')
            ->setPort(1234);

        $err = $client->serviceRegister($svc);
        $this->assertNull($err, sprintf('AgentClient::serviceRegister returned error: %s', $err));
    }

    /**
     * @depends testCanConstructAgentClient
     */
    public function testCanRegisterServiceWithOneCheck() {
        $client = new AgentClient(new Config());

        $svc = new AgentServiceRegistration();
        $svc
            ->setName(self::Service2Name)
            ->setAddress('127.0.0.1')
            ->setPort(4321)
            ->setCheck(new AgentServiceCheck([
                'TTL' => '5s',
            ]));

        $err = $client->serviceRegister($svc);
        $this->assertNull($err, sprintf('AgentClient::serviceRegister returned error: %s', $err));
    }

    /**
     * TODO: Expand later with multi-service return test?
     *
     * @depends testCanRegisterServiceNoChecks
     */
    public function testCanGetServiceList() {
        $client = new AgentClient(new Config());

        list($svcs, $err) = $client->services();

        try {
            $this->assertNull($err, sprintf('AgentClient::services return error: %s', $err));
            $this->assertInternalType('array', $svcs);
            $this->assertContainsOnlyInstancesOf(AgentService::class, $svcs);

            // NOTE: will always contain "consul" service
            $this->assertCount(2, $svcs);

        } catch (AssertionFailedError $e) {
            echo "\nservices list:\n";
            var_dump($svcs);
            echo "\n";

            throw $e;
        }
    }

    /**
     * @depends testCanRegisterServiceNoChecks
     */
    public function testCanDeregisterService() {
        $client = new AgentClient(new Config());

        $err = $client->serviceDeregister(self::Service1Name);
        $this->assertNull($err, sprintf('AgentClient::serviceDeregister returned error: %s', $err));

        list($svcs, $err) = $client->services();

        try {
            $this->assertNull($err, sprintf('AgentClient::services returned error: %s', $err));
            $this->assertInternalType('array', $svcs);
            $this->assertContainsOnlyInstancesOf(AgentService::class, $svcs);
            $this->assertCount(1, $svcs);
        } catch (AssertionFailedError $e) {
            echo "\nservices list:\n";
            var_dump($svcs);
            echo "\n";

            throw $e;
        }
    }

    /**
     * @depends testCanDeregisterService
     */
    public function testCanRegisterServiceWithCheck() {
        $client = new AgentClient(new Config());

        $svc = new AgentServiceRegistration();
        $svc
            ->setName(self::Service1Name)
            ->setPort(1234)
            ->setAddress('127.0.0.1')
            ->setCheck(new AgentServiceCheck([
                'TCP'      => '127.0.0.1',
                'Interval' => '30s',
            ]));

        $err = $client->serviceRegister($svc);
        $this->assertNull($err, sprintf('Error registering service with check: %s', $err));

        sleep(2);

        list($svcs, $err) = $client->services();

        try {
            $this->assertNull($err, sprintf('AgentClient::services returned error: %s', $err));
            $this->assertInternalType('array', $svcs);
            $this->assertContainsOnlyInstancesOf(AgentService::class, $svcs);
            $this->assertCount(2, $svcs);
        } catch (\PHPUnit_Framework_AssertionFailedError $e) {
            echo "\nservices list:\n";
            var_dump($svcs);
            echo "\n";

            throw $e;
        }

        $err = $client->serviceDeregister(self::Service1Name);
        $this->assertNull($err, sprintf('Error deregistering service: %s', $err));
    }


}