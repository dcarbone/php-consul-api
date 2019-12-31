<?php namespace DCarbone\PHPConsulAPITests\Usage\Catalog;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\WriteMeta;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;
use PHPUnit\Framework\AssertionFailedError;

/**
 * Class CatalogClientTest
 * @package DCarbone\PHPConsulAPITests\Usage\Catalog
 */
class CatalogClientTest extends AbstractUsageTests {
    const ServiceID1 = 'test1';
    const ServiceID2 = 'test2';

    const ServiceName = 'testservice';
    const ServicePort = 1234;
    const ServiceAddress = '127.0.0.1';

    /** @var bool */
    protected static $singlePerClass = true;

    public function testCanConstructClient() {
        $client = new CatalogClient(new Config());
        $this->assertInstanceOf(CatalogClient::class, $client);
    }

    /**
     * @depends testCanConstructClient
     */
    public function testCanRegisterService() {
        $client = new CatalogClient(new Config());

        $registration = new CatalogRegistration([
            'Node'    => 'dc1',
            'Address' => self::ServiceAddress,
            'Service' => new AgentService([
                'ID'      => self::ServiceID1,
                'Service' => self::ServiceName,
                'Port'    => self::ServicePort,
                'Adress'  => self::ServiceAddress,
            ]),
        ]);

        list($wm, $err) = $client->Register($registration);
        $this->assertNull($err, 'CatalogClient::register returned error: '.$err);
        $this->assertInstanceOf(WriteMeta::class, $wm);
    }

    /**
     * @depends testCanRegisterService
     */
    public function testCanGetService() {
        $client = new CatalogClient(new Config());

        list($service, $qm, $err) = $client->Service(self::ServiceName);
        $this->assertNull($err, 'CatalogClient::service returned error: '.$err);
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertIsArray($service);
        $this->assertCount(1, $service);
        $this->assertInstanceOf(CatalogService::class, reset($service));
    }

    /**
     * @depends testCanGetService
     */
    public function testCanRegisterSecondServiceWithSameName() {
        $client = new CatalogClient(new Config());

        list($wm, $err) = $client->Register(new CatalogRegistration([
            'Node'    => 'dc1',
            'Address' => self::ServiceAddress,
            'Service' => new AgentService([
                'ID'      => self::ServiceID2,
                'Service' => self::ServiceName,
                'Port'    => self::ServicePort,
                'Address' => self::ServiceAddress,
            ]),
        ]));

        $this->assertNull($err, 'CatalogClient::register failed to register second service: '.$err);
        $this->assertInstanceOf(WriteMeta::class, $wm);
    }

    /**
     * @depends testCanRegisterSecondServiceWithSameName
     */
    public function testCanGetListOfService() {
        $client = new CatalogClient(new Config());

        list($service, $qm, $err) = $client->Service(self::ServiceName);
        $this->assertNull($err, 'CatalogClient::service returned error: '.$err);
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertIsArray($service);

        try {
            $this->assertCount(2, $service);
            foreach ($service as $s) {
                $this->assertInstanceOf(CatalogService::class, $s);
            }
        } catch (AssertionFailedError $e) {
            var_dump($service);
            throw $e;
        }
    }

    /**
     * @depends testCanRegisterSecondServiceWithSameName
     */
    public function testCanGetListOfServices() {
        $client = new CatalogClient(new Config());

        list($services, $qm, $err) = $client->Services();

        try {
            $this->assertNull($err, 'CatalogClient::services returned error: '.$err);
            $this->assertInstanceOf(QueryMeta::class, $qm);
            $this->assertIsArray($services);
            $this->assertCount(2, $services);
            $this->assertContainsOnly('array', $services);
        } catch (AssertionFailedError $e) {
            var_dump($services);
            throw $e;
        }
    }

    /**
     * testCanGetListOfService
     */
    public function testCanDeregisterService() {
        $client = new CatalogClient(new Config());


        list($wm, $err) = $client->Deregister(new CatalogDeregistration([
            'Node'      => 'dc1',
            'ServiceID' => self::ServiceID1,
        ]));
        $this->assertNull($err, 'CatalogClient::deregister returned error: '.$err);
        $this->assertInstanceOf(WriteMeta::class, $wm);

        list($service, $qm, $err) = $client->Service(self::ServiceName);
        $this->assertNull($err, 'CatalogClient::service returned error: '.$err);
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertCount(1, $service);
        $this->assertInstanceOf(CatalogService::class, reset($service));
    }

    /**
     * TODO: Update after multi-datacenter tests are possible...
     *
     * @depends testCanConstructClient
     */
    public function testCanGetDatacenters() {
        $client = new CatalogClient(new Config());

        list($dcs, $err) = $client->Datacenters();

        try {
            $this->assertNull($err, 'CatalogClient::datacenters returned error: '.$err);
            $this->assertIsArray($dcs);
            $this->assertCount(1, $dcs);
            $this->assertEquals('dc1', $dcs[0]);
        } catch (AssertionFailedError $e) {
            var_dump($dcs);
            throw $e;
        }
    }

    /**
     * @depends testCanConstructClient
     */
    public function testCanGetListOfNodes() {
        $client = new CatalogClient(new Config());

        list($nodes, $qm, $err) = $client->Nodes();
        try {
            $this->assertNull($err, 'CatalogClient::nodes returned error: '.$err);
            $this->assertInstanceOf(QueryMeta::class, $qm);
            $this->assertIsArray($nodes);
            // TODO: figure out why there are 2 nodes returned by this call...
            $this->assertCount(2, $nodes);
            $this->assertContainsOnlyInstancesOf(CatalogNode::class, $nodes);
        } catch (AssertionFailedError $e) {
            var_dump($nodes);
            throw $e;
        }
    }

    /**
     * @depends testCanGetListOfNodes
     */
    public function testCanGetNode() {
        $client = new CatalogClient(new Config());

        list($nodes) = $client->Nodes();
        try {
            $this->assertIsArray($nodes);
            $this->assertCount(2, $nodes);
            $this->assertContainsOnlyInstancesOf(CatalogNode::class, $nodes);

            $id = null;
            foreach ($nodes as $node) {
                if ('' !== $node->ID) {
                    $id = $node->ID;
                    break;
                }
            }

            $this->assertNotNull($id, 'Unable to get node with ID');
        } catch (AssertionFailedError $e) {
            var_dump($nodes);
            throw $e;
        }

        list($node, $qm, $err) = $client->Node($id);
        try {
            $this->assertNull($err, 'CatalogClient::node returned error: '.$err);
            $this->assertInstanceOf(QueryMeta::class, $qm);
            $this->assertInstanceOf(CatalogNode::class, $node);
        } catch (AssertionFailedError $e) {
            var_dump($node);
            throw $e;
        }
    }
}