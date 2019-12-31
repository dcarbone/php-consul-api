<?php namespace DCarbone\PHPConsulAPITests\Usage\Coordinate;

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

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateClient;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class CoordinateClientTest
 * @package DCarbone\PHPConsulAPITests\Usage\Coordinate
 */
class CoordinateClientTest extends AbstractUsageTests {
    /** @var bool */
    protected static $singlePerClass = true;

    public function testCanConstructClient() {
        $client = new CoordinateClient(new Config());
        $this->assertInstanceOf(CoordinateClient::class, $client);
    }

    /**
     * @depends testCanConstructClient
     */
    public function testDatacenters() {
        $client = new CoordinateClient(new Config());

        list($dcs, $err) = $client->Datacenters();
        $this->assertNull($err, sprintf('CoordinateClient::datacenters() - %s', $err));
        $this->assertIsArray($dcs);
        $this->assertGreaterThan(0, count($dcs), 'Expected at least 1 datacenter');
    }

    /**
     * @depends testCanConstructClient
     */
    public function testNodes() {
        $client = new CoordinateClient(new Config());

        list($nodes, $qm, $err) = $client->Nodes();
        $this->assertNull($err, sprintf('CoordinateClient::nodes() - %s', $err));
        $this->assertInstanceOf(QueryMeta::class, $qm);
        $this->assertIsArray($nodes);
    }
}