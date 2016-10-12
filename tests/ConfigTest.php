<?php namespace DCarbone\PHPConsulAPITests;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class ConfigTest
 * @package DCarbone\PHPConsulAPITests
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    use PublicPropertyTestsTrait;

    /**
     * @inheritDoc
     */
    protected function getReflectionClass()
    {
        if (!isset($this->reflectionClass))
            $this->reflectionClass = new \ReflectionClass(Config::class);

        return $this->reflectionClass;
    }

    /**
     * @inheritDoc
     */
    protected function getEmptyInstance()
    {
        if (!isset($this->emptyInstance))
            $this->emptyInstance = new Config();

        return $this->emptyInstance;
    }

    /**
     * @test
     */
    public function assertGetterMethodsExist()
    {
        $config = $this->getEmptyInstance();

        $this->assertTrue(method_exists($config, 'getAddress'), 'Config::getAddress() must exist');
        $this->assertTrue(method_exists($config, 'getScheme'), 'Config::getScheme() must exist');
        $this->assertTrue(method_exists($config, 'getDatacenter'), 'Config::getDatacenter() must exist');
//        $this->assertTrue(method_exists($config, 'getWaitTime'))
    }
}
