<?php

namespace DCarbone\PHPConsulAPITests\Usage\Catalog;

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

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Catalog\CatalogClient;
use DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration;
use DCarbone\PHPConsulAPI\Catalog\CatalogNode;
use DCarbone\PHPConsulAPI\Catalog\CatalogRegistration;
use DCarbone\PHPConsulAPI\Catalog\CatalogService;
use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;
use PHPUnit\Framework\AssertionFailedError;

/**
 * Class CatalogClientTest
 *
 * @internal
 */
final class CatalogClientTest extends AbstractUsageTests
{
    public const ServiceID1 = 'test1';
    public const ServiceID2 = 'test2';

    public const ServiceName    = 'testservice';
    public const ServicePort    = 1234;
    public const ServiceAddress = '10.2.3.4';

    /** @var bool */
    protected static bool $singlePerClass = true;

    public function testCanConstructClient(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());
        self::assertInstanceOf(CatalogClient::class, $client);
    }

    /**
     * @depends testCanConstructClient
     */
    public function testCanRegisterService(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());

        $registration = new CatalogRegistration([
            'Node'    => 'dc1',
            'Address' => self::ServiceAddress,
            'Service' => new AgentService([
                'ID'       => self::ServiceID1,
                'Service'  => self::ServiceName,
                'Port'     => self::ServicePort,
                'Address'  => self::ServiceAddress,
            ]),
        ]);

        [$wm, $err] = $client->Register($registration);
        self::assertNull($err, 'CatalogClient::register returned error: ' . $err);
        self::assertInstanceOf(WriteMeta::class, $wm);
    }

    /**
     * @depends testCanRegisterService
     */
    public function testCanGetService(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());

        [$service, $qm, $err] = $client->Service(self::ServiceName);
        self::assertNull($err, 'CatalogClient::service returned error: ' . $err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($service);
        self::assertCount(1, $service);
        self::assertInstanceOf(CatalogService::class, \reset($service));
    }

    /**
     * @depends testCanGetService
     */
    public function testCanRegisterSecondServiceWithSameName(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());

        [$wm, $err] = $client->Register(new CatalogRegistration([
            'Node'    => 'dc1',
            'Address' => self::ServiceAddress,
            'Service' => new AgentService([
                'ID'      => self::ServiceID2,
                'Service' => self::ServiceName,
                'Port'    => self::ServicePort,
                'Address' => self::ServiceAddress,
            ]),
        ]));

        self::assertNull($err, 'CatalogClient::register failed to register second service: ' . $err);
        self::assertInstanceOf(WriteMeta::class, $wm);
    }

    /**
     * @depends testCanRegisterSecondServiceWithSameName
     */
    public function testCanGetListOfService(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());

        [$service, $qm, $err] = $client->Service(self::ServiceName);
        self::assertNull($err, 'CatalogClient::service returned error: ' . $err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($service);

        try {
            self::assertCount(2, $service);
            foreach ($service as $s) {
                self::assertInstanceOf(CatalogService::class, $s);
            }
        } catch (AssertionFailedError $e) {
            \var_dump($service);
            throw $e;
        }
    }

    /**
     * @depends testCanRegisterSecondServiceWithSameName
     */
    public function testCanGetListOfServices(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());

        [$services, $qm, $err] = $client->Services();

        try {
            self::assertNull($err, 'CatalogClient::services returned error: ' . $err);
            self::assertInstanceOf(QueryMeta::class, $qm);
            self::assertIsArray($services);
            self::assertCount(2, $services);
            self::assertContainsOnly('array', $services);
        } catch (AssertionFailedError $e) {
            \var_dump($services);
            throw $e;
        }
    }

    /**
     * testCanGetListOfService
     */
    public function testCanDeregisterService(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());

        [$wm, $err] = $client->Deregister(new CatalogDeregistration([
            'Node'      => 'dc1',
            'ServiceID' => self::ServiceID1,
        ]));
        self::assertNull($err, 'CatalogClient::deregister returned error: ' . $err);
        self::assertInstanceOf(WriteMeta::class, $wm);

        [$service, $qm, $err] = $client->Service(self::ServiceName);
        self::assertNull($err, 'CatalogClient::service returned error: ' . $err);
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertCount(1, $service);
        self::assertInstanceOf(CatalogService::class, \reset($service));
    }

    /**
     * TODO: Update after multi-datacenter tests are possible...
     *
     * @depends testCanConstructClient
     */
    public function testCanGetDatacenters(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());

        [$dcs, $err] = $client->Datacenters();

        try {
            self::assertNull($err, 'CatalogClient::datacenters returned error: ' . $err);
            self::assertIsArray($dcs);
            self::assertCount(1, $dcs);
            self::assertSame('dc1', $dcs[0]);
        } catch (AssertionFailedError $e) {
            \var_dump($dcs);
            throw $e;
        }
    }

    /**
     * @depends testCanConstructClient
     */
    public function testCanGetListOfNodes(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());

        [$nodes, $qm, $err] = $client->Nodes();
        try {
            self::assertNull($err, 'CatalogClient::nodes returned error: ' . $err);
            self::assertInstanceOf(QueryMeta::class, $qm);
            self::assertIsArray($nodes);
            // TODO: figure out why there are 2 nodes returned by this call...
            self::assertCount(2, $nodes);
            self::assertContainsOnlyInstancesOf(Node::class, $nodes);
        } catch (AssertionFailedError $e) {
            \var_dump($nodes);
            throw $e;
        }
    }

    /**
     * @depends testCanGetListOfNodes
     */
    public function testCanGetNode(): void
    {
        $client = new CatalogClient(ConsulManager::testConfig());

        [$nodes] = $client->Nodes();
        try {
            self::assertIsArray($nodes);
            self::assertCount(2, $nodes);
            self::assertContainsOnlyInstancesOf(Node::class, $nodes);

            $id = null;
            foreach ($nodes as $node) {
                if ('' !== $node->ID) {
                    $id = $node->ID;
                    break;
                }
            }

            self::assertNotNull($id, 'Unable to get node with ID');
        } catch (AssertionFailedError $e) {
            \var_dump($nodes);
            throw $e;
        }

        [$node, $qm, $err] = $client->Node($id);
        try {
            self::assertNull($err, 'CatalogClient::node returned error: ' . $err);
            self::assertInstanceOf(QueryMeta::class, $qm);
            self::assertInstanceOf(CatalogNode::class, $node);
        } catch (AssertionFailedError $e) {
            \var_dump($node);
            throw $e;
        }
    }
}
