<?php

namespace DCarbone\PHPConsulAPITests\Usage;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPITests\ConsulManager;
use PHPUnit\Framework\TestCase;

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

/**
 * Class ConfigUsageTest
 *
 * @internal
 */
final class ConfigUsageTest extends TestCase
{
    public const DEFAULT_ADDRESS = '127.0.0.1:8500';
    public const DEFAULT_SCHEME  = 'http';

    public function testCanConstructConfig(): void
    {
        $config = new Config();
        self::assertInstanceOf(Config::class, $config);
    }

    /**
     * @depends testCanConstructConfig
     */
    public function testConfigDefaults(): void
    {
        $config = new Config();

        $expectedAddress = $_ENV[Consul::HTTPAddrEnvName] ?: self::DEFAULT_ADDRESS;
        $expectedScheme  = $_ENV[Consul::HTTPSSLEnvName] ? 'https' : self::DEFAULT_SCHEME;

        self::assertSame(
            $expectedAddress,
            $config->getAddress(),
            sprintf('Default address is not "%s"', $expectedAddress)
        );
        self::assertSame(
            $expectedScheme,
            $config->getScheme(),
            sprintf('Default scheme is not "%s"', $expectedScheme)
        );
        self::assertNotNull($config->getHttpClient(), 'HttpClient is null');
        self::assertFalse($config->isInsecureSkipVerify(), 'InsecureSkipVerify is not false');
    }

    protected function setUp(): void
    {
        ConsulManager::startSingleDev();
    }

    protected function tearDown(): void
    {
        ConsulManager::stopSingle();
    }
}
