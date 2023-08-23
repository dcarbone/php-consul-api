<?php

namespace DCarbone\PHPConsulAPITests\Usage\Random;

/*
   Copyright 2016-2023 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\KV\KVPair;
use PHPUnit\Framework\TestCase;

/**
 * Class KVClientTest
 *
 * @internal
 */
final class RandomWhateverTest extends TestCase
{
    public function testDynamicFieldAssignment(): void
    {
        static $k = 'nope';
        static $v = 'should not be here';

        $json = sprintf('{"%s":%s"}', $k, $v);

        $dec = json_decode($json);

        $this->assertIsObject($dec);

        $kvp = new KVPair($dec, false);

        self::assertEquals($v, $kvp->{$k});
    }
}
