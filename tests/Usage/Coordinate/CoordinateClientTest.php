<?php

namespace DCarbone\PHPConsulAPITests\Usage\Coordinate;

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

use DCarbone\PHPConsulAPI\Coordinate\CoordinateClient;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class CoordinateClientTest
 *
 * @internal
 */
final class CoordinateClientTest extends AbstractUsageTests
{
    /** @var bool */
    protected static bool $singlePerClass = true;

    public function testCanConstructClient(): void
    {
        $client = new CoordinateClient(ConsulManager::testConfig());
        self::assertInstanceOf(CoordinateClient::class, $client);
    }

    /**
     * @depends testCanConstructClient
     */
    public function testDatacenters(): void
    {
        $client = new CoordinateClient(ConsulManager::testConfig());

        [$dcs, $err] = $client->Datacenters();
        self::assertNull($err, sprintf('CoordinateClient::datacenters() - %s', $err));
        self::assertIsArray($dcs);
        self::assertGreaterThan(0, count($dcs), 'Expected at least 1 datacenter');
    }

    /**
     * @depends testCanConstructClient
     */
    public function testNodes(): void
    {
        $client = new CoordinateClient(ConsulManager::testConfig());

        [$nodes, $qm, $err] = $client->Nodes();
        self::assertNull($err, sprintf('CoordinateClient::nodes() - %s', $err));
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertIsArray($nodes);
    }
}
