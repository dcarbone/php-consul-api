<?php namespace DCarbone\PHPConsulAPITests\Usage;

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

use DCarbone\PHPConsulAPI\ACL\ACLClient;
use DCarbone\PHPConsulAPI\Agent\AgentClient;
use DCarbone\PHPConsulAPI\Catalog\CatalogClient;
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateClient;
use DCarbone\PHPConsulAPI\Event\EventClient;
use DCarbone\PHPConsulAPI\Health\HealthClient;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\Operator\OperatorClient;
use DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryClient;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Status\StatusClient;

/**
 * Class ConsulUsageTest
 * @package DCarbone\PHPConsulAPITests\Usage
 */
class ConsulUsageTest extends \PHPUnit_Framework_TestCase {

    public function testCanConstructWithoutConfig() {
        $consul = new Consul();
        $this->assertInstanceOf(Consul::class, $consul);
    }

    /**
     * @depends testCanConstructWithoutConfig
     */
    public function testConsulHasClientsAsProperties() {
        $this->assertClassHasAttribute('ACL', Consul::class);
        $this->assertClassHasAttribute('Agent', Consul::class);
        $this->assertClassHasAttribute('Catalog', Consul::class);
        $this->assertClassHasAttribute('Coordinate', Consul::class);
        $this->assertClassHasAttribute('Event', Consul::class);
        $this->assertClassHasAttribute('Health', Consul::class);
        $this->assertClassHasAttribute('KV', Consul::class);
        $this->assertClassHasAttribute('Operator', Consul::class);
        $this->assertClassHasAttribute('PreparedQuery', Consul::class);
        $this->assertClassHasAttribute('Session', Consul::class);
        $this->assertClassHasAttribute('Status', Consul::class);

        $consul = new Consul();

        $this->assertInstanceOf(ACLClient::class, $consul->ACL);
        $this->assertInstanceOf(AgentClient::class, $consul->Agent);
        $this->assertInstanceOf(CatalogClient::class, $consul->Catalog);
        $this->assertInstanceOf(CoordinateClient::class, $consul->Coordinate);
        $this->assertInstanceOf(EventClient::class, $consul->Event);
        $this->assertInstanceOf(HealthClient::class, $consul->Health);
        $this->assertInstanceOf(KVClient::class, $consul->KV);
        $this->assertInstanceOf(OperatorClient::class, $consul->Operator);
        $this->assertInstanceOf(PreparedQueryClient::class, $consul->PreparedQuery);
        $this->assertInstanceOf(SessionClient::class, $consul->Session);
        $this->assertInstanceOf(StatusClient::class, $consul->Status);
    }

    /**
     * @depends testConsulHasClientsAsProperties
     */
    public function testCanConstructWithConfig() {
        $config = new Config(['Address' => '123.456.789:8500']);
        $consul = new Consul($config);
        $this->assertInstanceOf(Consul::class, $consul);

        $this->assertEquals('123.456.789:8500', $consul->KV->getConfig()->getAddress());
    }
}