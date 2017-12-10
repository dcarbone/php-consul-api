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
class TimeDuration implements \JsonSerializable {
    const Nanosecond = 1;
    const Microsecond = 1000 * self::Nanosecond;
    const Millisecond = 1000 * self::Microsecond;
    const Second = 1000 * self::Millisecond;
    const Minute = 60 * self::Second;
    const Hour = 60 * self::Minute;

    /** @var int */
    private $nanoseconds = 0;

    /**
     * TimeDuration constructor.
     * @param int $nanoseconds
     */
    public function __construct(int $nanoseconds = 0) {
        $this->nanoseconds = $nanoseconds;
    }

    /**
     * @return int
     */
    public function Nanoseconds(): int {
        return $this->nanoseconds;
    }

    /**
     * @return float
     */
    public function Seconds(): float {
        $sec = $this->nanoseconds / self::Second;
        $nsec = $this->nanoseconds % self::Second;
        return $sec + $nsec / PHPCONSULAPI_FLOAT_DIVISOR;
    }

    /**
     * @return float
     */
    public function Minutes(): float {
        $min = $this->nanoseconds / self::Minute;
        $nsec = $this->nanoseconds % self::Minute;
        return $min + $nsec / (60 * PHPCONSULAPI_FLOAT_DIVISOR);
    }

    /**
     * @return float
     */
    public function Hours(): float {
        $hour = $this->nanoseconds / self::Hour;
        $nsec = $this->nanoseconds % self::Hour;
        return $hour + $nsec / (60 * 60 * PHPCONSULAPI_FLOAT_DIVISOR);
    }

    /**
     * @return \DateTime
     */
    public function DateTime(): \DateTime {
        return \DateTime::createFromFormat('U', $this->Seconds());
    }

    /**
     * @return int
     */
    public function jsonSerialize() {
        return $this->nanoseconds;
    }

    /**
     * @param string $s
     * @return \DCarbone\PHPConsulAPI\TimeDuration
     */
    public static function ParseDuration(string $s): TimeDuration {
        $orig = $s;
        $d = 0;
        $neg = false;

        if ('' !== $s) {
            $c = $s[0];
            if ('-' === $c || '+' === $c) {
                $neg = '-' === $c;
                $s = substr($s, 1);
            }
        }
        if ('0' === $s) {
            return new TimeDuration(0);
        }
        if ('' === $s) {
            throw new \InvalidArgumentException("Invalid duration: {$orig}");
        }
        while ('' !== $s) {
            $v = $f = 0;
            $scale = 1.0;
            if (!('.' === $s[0] || 0 <= $s[0] && $s[0] <= 9)) {
                throw new \InvalidArgumentException("Invalid duration: {$orig}");
            }
            $pl = strlen($s);

        }
    }

    /**
     * TODO: improve efficiency a bit...
     *
     * @return string
     */
    public function __toString() {
        if (0 === $this->nanoseconds) {
            return '0s';
        }

        $buff = '';

        $u = $this->nanoseconds;
        $neg = $this->nanoseconds < 0;
        if ($neg) {
            $u = -$u;
        }

        if ($u < self::Second) {
            $prec = 0;
            switch (true) {
                case $u < self::Microsecond:
                    $buff = 'ns';
                    break;
                case $u < self::Millisecond:
                    $prec = 3;
                    $buff = 'µs';
                    break;
                default:
                    $prec = 6;
                    $buff = 'ms';
            }
            $u = self::fmtFrac($buff, $u, $prec);
            self::fmtInt($buff, $u);
        } else {
            $buff = "s{$buff}";
            $u = self::fmtFrac($buff, $u, 9);

            self::fmtInt($buff, $u % 60);

            $u /= 60;

            if ($u > 0) {
                $buff = "m{$buff}";

                self::fmtInt($buff, $u % 60);
                $u /= 60;

                if ($u > 0) {
                    $buff = "h{$buff}";
                    self::fmtInt($buff, $u);
                }
            }
        }

        if ($neg) {
            $buff = "-{$buff}";
        }

        return trim($buff);
    }

    /**
     * @param string $buff
     * @param int $v
     * @param int $prec
     * @return int
     */
    protected static function fmtFrac(string &$buff, int $v, int $prec): int {
        $print = false;
        for ($i = 0; $i < $prec; $i++) {
            $digit = $v % 10;
            $print = $print || $digit != 0;
            if ($print) {
                $buff = "{$digit}{$buff}";
            }
            $v /= 10;
        }
        if ($print) {
            $buff = ".{$buff}";
        }
        return $v;
    }

    /**
     * @param string $buff
     * @param int $v
     */
    protected static function fmtInt(string &$buff, int $v): void {
        while ($v > 0) {
            $buff = sprintf('%d%s', $v % 10, $buff);
            $v = intval($v /= 10, 10);
        }
    }
}
