<?php namespace DCarbone\PHPConsulAPITests\Usage\Coordinate;

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

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateClient;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class CoordinateClientTest
 *
 * @internal
 */
final class CoordinateClientTest extends AbstractUsageTests
{
    /** @var bool */
    protected static $singlePerClass = true;

    public function testCanConstructClient(): void
    {
        $client = new CoordinateClient(new Config());
        static::assertInstanceOf(CoordinateClient::class, $client);
    }

    /**
     * @depends testCanConstructClient
     */
    public function testDatacenters(): void
    {
        $client = new CoordinateClient(new Config());

        [$dcs, $err] = $client->Datacenters();
        static::assertNull($err, \sprintf('CoordinateClient::datacenters() - %s', $err));
        static::assertIsArray($dcs);
        static::assertGreaterThan(0, \count($dcs), 'Expected at least 1 datacenter');
    }

    /**
     * @depends testCanConstructClient
     */
    public function testNodes(): void
    {
        $client = new CoordinateClient(new Config());

        [$nodes, $qm, $err] = $client->Nodes();
        static::assertNull($err, \sprintf('CoordinateClient::nodes() - %s', $err));
        static::assertInstanceOf(QueryMeta::class, $qm);
        static::assertIsArray($nodes);
    }
}
