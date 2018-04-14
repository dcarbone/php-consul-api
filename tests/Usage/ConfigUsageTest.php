<?php namespace DCarbone\PHPConsulAPITests\Usage;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPITests\ConsulManager;
use PHPUnit\Framework\TestCase;

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

/**
 * Class ConfigUsageTest
 * @package DCarbone\PHPConsulAPITests\Usage
 */
class ConfigUsageTest extends TestCase {
    const DEFAULT_ADDRESS = '127.0.0.1:8500';
    const DEFAULT_SCHEME = 'http';

    protected function setUp() {
        ConsulManager::startSingleDev();
    }

    protected function tearDown() {
        ConsulManager::stopSingle();
    }

    /**
     * @return Config
     */
    public function testCanConstructConfig() {
        $config = new Config();
        $this->assertInstanceOf(Config::class, $config);
        return $config;
    }

    /**
     * @depends testCanConstructConfig
     */
    public function testConfigDefaults() {
        $config = new Config();

        $expectedAddress = $_ENV[Consul::HTTPAddrEnvName] ?: self::DEFAULT_ADDRESS;
        $expectedScheme = $_ENV[Consul::HTTPSSLEnvName] ? 'https' : self::DEFAULT_SCHEME;

        $this->assertEquals($expectedAddress,
            $config->getAddress(),
            sprintf('Default address is not "%s"', $expectedAddress));
        $this->assertEquals($expectedScheme,
            $config->getScheme(),
            sprintf('Default scheme is not "%s"', $expectedScheme));
        $this->assertNotNull($config->getHttpClient(), 'HttpClient is null');
        $this->assertFalse($config->isInsecureSkipVerify(), 'InsecureSkipVerify is not false');
        $this->assertFalse($config->isTokenInHeader(), 'TokenInHeader is not false');
    }
}