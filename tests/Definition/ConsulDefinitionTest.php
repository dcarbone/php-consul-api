<?php namespace DCarbone\PHPConsulAPITests\Definition;

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
use DCarbone\PHPConsulAPI\Consul;
use PHPUnit\Framework\TestCase;

/**
 * Class ConsulDefinitionTest
 *
 * @internal
 */
final class ConsulDefinitionTest extends TestCase
{
    protected $clientClassnames = [];

    /**
     * @return \DCarbone\PHPConsulAPITests\Definition\Consul
     */
    public function testCanConstructWithoutConfig()
    {
        $consul = new Consul();
        static::assertInstanceOf(Consul::class, $consul);

        return $consul;
    }

    /**
     * @depends testCanConstructWithoutConfig
     * @param \DCarbone\PHPConsulAPITests\Definition\Consul $consul
     */
    public function testAllClientsRepresented(Consul $consul): void
    {
        foreach ($this->clientClassnames as $clientClass) {
            $exp      = \explode('\\', $clientClass);
            $propName = \str_replace(['.php', 'Client'], '', \end($exp));
            static::assertClassHasAttribute($propName, Consul::class);
            // TODO: refactor this in line with new shit: https://github.com/sebastianbergmann/phpunit/issues/3339
//            $this->assertAttributeInstanceOf($clientClass, $propName, $consul);
        }
    }

    /**
     * @depends testAllClientsRepresented
     */
    public function testCanConstructWithConfig(): void
    {
        $config = new Config(['Address' => '123.456.789:8500']);
        $consul = new Consul($config);
        static::assertInstanceOf(Consul::class, $consul);

        static::assertSame('123.456.789:8500', $consul->KV->getConfig()->getAddress());
    }

    protected function setUp(): void
    {
        foreach (\glob(__DIR__ . '/../../src/*', \GLOB_ONLYDIR) as $dir) {
            $exp                      = \explode('/', $dir);
            $this->clientClassnames[] = \sprintf(
                'DCarbone\\PHPConsulAPI\\%s\\%1$sClient',
                \end($exp)
            );
        }
    }
}
