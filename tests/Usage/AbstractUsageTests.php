<?php namespace DCarbone\PHPConsulAPITests\Usage;

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

use DCarbone\PHPConsulAPITests\ConsulManager;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractUsageTests
 * @package DCarbone\PHPConsulAPITests\Usage
 */
abstract class AbstractUsageTests extends TestCase {
    /** @var bool */
    protected static $singlePerClass = false;
    /** @var bool */
    protected $singlePerTest = false;

    public static function setUpBeforeClass() {
        if (static::$singlePerClass) {
            ConsulManager::startSingleDev();
        }
    }

    public static function tearDownAfterClass() {
        ConsulManager::stopSingle();
    }

    protected function setUp() {
        if ($this->singlePerTest) {
            ConsulManager::startSingleDev();
        }
    }

    protected function tearDown() {
        if ($this->singlePerTest) {
            ConsulManager::stopSingle();
        }
    }
}