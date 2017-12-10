<?php namespace DCarbone\PHPConsulAPITests\Usage;

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

use DCarbone\PHPConsulAPI\TimeDuration;
use PHPUnit\Framework\TestCase;

/**
 * Class TimeDurationTest
 * @package DCarbone\PHPConsulAPITests\Usage
 */
class TimeDurationTest extends TestCase {
    public function testCanConstructEmpty() {
        $d = new TimeDuration();
        $this->assertInstanceOf(TimeDuration::class, $d);
    }

    /**
     * @depends testCanConstructEmpty
     */
    public function testCanConstructWithValue() {
        $n = time() * TimeDuration::Second;
        $d = new TimeDuration($n);
        $this->assertInstanceOf(TimeDuration::class, $d);
        $this->assertEquals($n, $d->Nanoseconds());
    }

    /**
     * @depends testCanConstructWithValue
     */
    public function testSubSecondFormat() {
        $this->assertEquals('500ms', (string)new TimeDuration(500 * TimeDuration::Millisecond));
        $this->assertEquals('500µs', (string)new TimeDuration(500 * TimeDuration::Microsecond));
        $this->assertEquals('500ns', (string)new TimeDuration(500 * TimeDuration::Nanosecond));
    }

    /**
     * @depends testCanConstructWithValue
     */
    public function testLargerThanSecondFormat() {
        $now = time();
        $d = new TimeDuration($now * TimeDuration::Second);
        preg_match('/^(\d+)h(\d+)m(\d+)s$/', (string)$d, $matches);
        $this->assertEquals((int)$d->Hours(), (int)$matches[1]);
        $t = \DateTime::createFromFormat('U', $now, new \DateTimeZone('UTC'));
        $this->assertEquals((int)$t->format('i'), (int)$matches[2], sprintf('Minute mismatch: %d %d', $matches[2], $t->format('m')));
        $this->assertEquals((int)$t->format('s'), (int)$matches[3], sprintf('Second mismatch: %d %d', $matches[3], $t->format('s')));
    }

    public function testParseDuration() {
        $d = TimeDuration::ParseDuration('1h2m3s4m5u6n');
    }
}