<?php namespace DCarbone\PHPConsulAPITests\Usage\Operator;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Operator\ReadableDuration;
use DCarbone\PHPConsulAPI\TimeDuration;
use PHPUnit\Framework\TestCase;

/**
 * Class ReadableDurationTest
 * @package DCarbone\PHPConsulAPITests\Usage\Operator
 */
class ReadableDurationTest extends TestCase {
    public function testReadableJsonEncoding() {
        $rd = new ReadableDuration(time() * TimeDuration::Second);
        $this->assertEquals('"'.(string)$rd.'"', json_encode($rd));
    }
}