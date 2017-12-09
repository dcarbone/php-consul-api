<?php namespace DCarbone\PHPConsulAPI;

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

/**
 * This class is a little clone of the Golang time.Duration type
 *
 * Class TimeDuration
 * @package DCarbone\PHPConsulAPI
 */
class TimeDuration {
    const Nanosecond = 1;
    const Microsecond = 1000 * self::Nanosecond;
    const Millisecond = 1000 * self::Microsecond;
    const Second = 1000 * self::Millisecond;
    const Minute = 60 * self::Second;
    const Hour = 60 * self::Minute;

    /** @var int */
    private $time = 0;

    /**
     * TimeDuration constructor.
     * @param int $nanoseconds
     */
    public function __construct(int $nanoseconds = 0) {
        $this->time = $nanoseconds;
    }

    /**
     * @return int
     */
    public function Nanoseconds(): int {
        return $this->time;
    }

    /**
     * @return float
     */
    public function Seconds(): float {
        $sec = $this->time / self::Second;
        $nsec = $this->time % self::Second;
        return $sec + $nsec / PHPCONSULAPI_FLOAT_DIVISOR;
    }

    /**
     * @return float
     */
    public function Minutes(): float {
        $min = $this->time / self::Minute;
        $nsec = $this->time % self::Minute;
        return $min + $nsec / (60 * PHPCONSULAPI_FLOAT_DIVISOR);
    }

    /**
     * @return float
     */
    public function Hours(): float {
        $hour = $this->time / self::Hour;
        $nsec = $this->time % self::Hour;
        return $hour + $nsec / (60 * 60 * PHPCONSULAPI_FLOAT_DIVISOR);
    }
}

(function() {
    $p = (int)ini_get('precision');
    if (0 === $p) {
        $p = 17;
        ini_set('precision', $p);
    }
    define('PHPCONSULAPI_FLOAT_PRECISION', $p);
    define('PHPCONSULAPI_FLOAT_DIVISOR', 1 * 10 ** $p);
})->call(new TimeDuration());