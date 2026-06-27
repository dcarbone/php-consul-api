<?php

namespace DCarbone\PHPConsulAPITests\Integration\KV;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPITests\ConsulManager;
use PHPUnit\Framework\TestCase;

/**
 * Basic client instantiation tests.
 *
 * @internal
 */
final class KVClientTest extends TestCase
{
    public function testCanConstructClient(): void
    {
        $kv = new KVClient(ConsulManager::testConfig());
        self::assertInstanceOf(KVClient::class, $kv);
    }

    public function testCanConstructViaConsul(): void
    {
        $consul = new Consul(ConsulManager::testConfig());
        self::assertInstanceOf(KVClient::class, $consul->KV);
        self::assertInstanceOf(KVClient::class, $consul->KV());
    }
}
