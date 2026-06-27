<?php

namespace DCarbone\PHPConsulAPITests\Integration\Random;

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

use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\Health\HealthChecks;
use DCarbone\PHPConsulAPI\Health\HealthClient;
use DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryClient;
use DCarbone\PHPConsulAPI\QueryMeta;
use DCarbone\PHPConsulAPI\Status\StatusClient;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractUsageTests;
use PHPUnit\Framework\Attributes\Depends;

/**
 * @internal
 */
final class ConsulAdditionalClientCoverageTest extends AbstractUsageTests
{
    /** @var bool */
    protected static bool $singlePerClass = true;

    public function testCanConstructViaConsulForAdditionalClients(): void
    {
        $consul = new Consul(ConsulManager::testConfig());

        self::assertInstanceOf(HealthClient::class, $consul->Health);
        self::assertInstanceOf(HealthClient::class, $consul->Health());
        self::assertInstanceOf(PreparedQueryClient::class, $consul->PreparedQuery);
        self::assertInstanceOf(PreparedQueryClient::class, $consul->PreparedQuery());
        self::assertInstanceOf(StatusClient::class, $consul->Status);
        self::assertInstanceOf(StatusClient::class, $consul->Status());
    }

    #[Depends('testCanConstructViaConsulForAdditionalClients')]
    public function testCanReadStatusEndpoints(): void
    {
        $consul = new Consul(ConsulManager::testConfig());

        [$leader, $err] = $consul->Status->Leader();
        self::assertNull($err, sprintf('StatusClient::Leader returned error: %s', $err));
        self::assertIsString($leader);
        self::assertNotSame('', $leader);

        [$peers, $err] = $consul->Status()->Peers();
        self::assertNull($err, sprintf('StatusClient::Peers returned error: %s', $err));
        self::assertIsArray($peers);
        self::assertContainsOnly('string', $peers);
        self::assertGreaterThanOrEqual(1, count($peers));
    }

    #[Depends('testCanConstructViaConsulForAdditionalClients')]
    public function testCanReadHealthChecksByNode(): void
    {
        $consul = new Consul(ConsulManager::testConfig());

        [$nodeName, $err] = $consul->Agent->NodeName();
        self::assertNull($err, sprintf('AgentClient::NodeName returned error: %s', $err));
        self::assertIsString($nodeName);
        self::assertNotSame('', $nodeName);

        [$checks, $qm, $err] = $consul->Health->Node($nodeName);
        self::assertNull($err, sprintf('HealthClient::Node returned error: %s', $err));
        self::assertInstanceOf(QueryMeta::class, $qm);
        self::assertInstanceOf(HealthChecks::class, $checks);
        self::assertGreaterThanOrEqual(1, count($checks));
    }

}
