<?php namespace DCarbone\PHPConsulAPI\Coordinate;

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

const ZeroThreshold = 1.0e-6;

/**
 * TODO: I am stupid, so this probably needs to be corrected.
 *
 * @param array $vec1
 * @param array $vec2
 * @return array(
 * @type array
 * @type float
 * )
 */
function unitVectorAt(array $vec1, array $vec2): array {
    $ret = diff($vec1, $vec2);

    if (($mag = magnitude($ret)) && $mag > ZeroThreshold) {
        return [mul($ret, 1.0 / $mag), (float)$mag];
    }

    foreach ($ret as $k => &$v) {
        $v = lcg_value() - 0.5;
    }

    if (($mag = magnitude($ret)) && $mag > ZeroThreshold) {
        return [mul($ret, 1.0 / $mag), 0.0];
    }

    $ret = array_fill(0, count($ret), 0.0);
    $ret[0] = 1.0;
    return $ret;
}

/**
 * @param array $vec1
 * @param array $vec2
 * @return array
 */
function add(array $vec1, array $vec2): array {
    $ret = [];
    foreach ($vec1 as $k => $v) {
        $ret[$k] = $v + $vec2[$k];
    }
    return $ret;
}

/**
 * @param array $vec1
 * @param array $vec2
 * @return array
 */
function diff(array $vec1, array $vec2): array {
    $ret = [];
    foreach ($vec1 as $k => $v) {
        $ret[$k] = $v - $vec2[$k];
    }
    return $ret;
}

/**
 * @param array $vec
 * @param float $factor
 * @return array
 */
function mul(array $vec, float $factor): array {
    $ret = [];
    foreach ($vec as $k => $v) {
        $ret[$k] = $v * $factor;
    }
    return $ret;
}

/**
 * @param array $vec
 * @return float
 */
function magnitude(array $vec): float {
    $sum = 0.0;
    foreach ($vec as $k => $v) {
        $sum += ($v * $v);
    }
    return sqrt($sum);
}