<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PHPLib;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use Brick\Math\BigInteger;
use Brick\Math\Exception\MathException;
use Brick\Math\NumberSyntax;
use DCarbone\Go\Time;

/**
 * Parses an RFC3339 timestamp from Consul, retaining nanosecond-precision fractional seconds.
 *
 * @param string $ts RFC3339 timestamp string (e.g. "2026-04-03T20:02:59.892792592-05:00")
 */
function parse_time(string $ts): null|Time\Time
{
    $t = Time\Time::createFromFormat(format: 'Y-m-d\TH:i:s.uP', datetime: $ts);
    if (false !== $t) {
        return $t;
    }

    $t = Time\Time::createFromFormat(format: \DATE_RFC3339, datetime: $ts);
    if (false !== $t) {
        return $t;
    }

    return null;
}

const UINT64_MAX = '18446744073709551615';

/**
 * Converts a value into a BigInteger, ensuring it is within the uint64 range.
 *
 * @throws \InvalidArgumentException
 */
function uint64(int|string|BigInteger $v): BigInteger
{
    try {
        $b = match (true) {
            $v instanceof BigInteger => $v,
            \is_int($v) => BigInteger::of($v),
            default => BigInteger::parse(value: $v, allowedSyntax: NumberSyntax::INTEGER, maxDigits: \strlen(UINT64_MAX)),
        };
    } catch (MathException $mathException) {
        throw new \InvalidArgumentException(sprintf('"%s" is not a valid uint64 value', $v), $mathException->getCode(), previous: $mathException);
    }
    if ($b->isNegative() || $b->isGreaterThan(UINT64_MAX)) {
        throw new \InvalidArgumentException(sprintf('%s is outside the uint64 range', $b));
    }
    return $b;
}

/**
 * Encodes a value as JSON, writing BigInteger values as JSON numbers rather than strings.
 *
 * JSON_PRETTY_PRINT is not supported.
 *
 * @throws \JsonException
 */
function encode_json(mixed $value, int $flags = 0, int $depth = 512): string
{
    if ($value instanceof BigInteger) {
        return $value->toString();
    }
    if ($value instanceof \JsonSerializable) {
        return encode_json(value: $value->jsonSerialize(), flags: $flags, depth: $depth);
    }
    if (is_array($value)) {
        if (0 === ($flags & \JSON_FORCE_OBJECT) && array_is_list($value)) {
            if (0 >= $depth) {
                throw new \JsonException('Maximum stack depth exceeded', \JSON_ERROR_DEPTH);
            }
            $parts = [];
            foreach ($value as $v) {
                $parts[] = encode_json(value: $v, flags: $flags, depth: $depth - 1);
            }
            return '[' . implode(',', $parts) . ']';
        }
        return _encode_json_object(fields: $value, flags: $flags, depth: $depth);
    }
    if (is_object($value) && !$value instanceof \UnitEnum) {
        return _encode_json_object(fields: get_object_vars($value), flags: $flags, depth: $depth);
    }
    return json_encode(value: $value, flags: $flags | \JSON_THROW_ON_ERROR);
}

/**
 * @param array<mixed> $fields
 *
 * @throws \JsonException
 */
function _encode_json_object(array $fields, int $flags, int $depth): string
{
    if (0 >= $depth) {
        throw new \JsonException('Maximum stack depth exceeded', \JSON_ERROR_DEPTH);
    }
    $parts = [];
    foreach ($fields as $k => $v) {
        $parts[] = json_encode(value: (string)$k, flags: ($flags & ~\JSON_NUMERIC_CHECK) | \JSON_THROW_ON_ERROR)
            . ':'
            . encode_json(value: $v, flags: $flags, depth: $depth - 1);
    }
    return '{' . implode(',', $parts) . '}';
}

function dur_to_millisecond(Time\Duration $dur): string
{
    $ns = $dur->Nanoseconds();
    $ms = $dur->Milliseconds();

    if (0 < $ns && 0 === (int)$ms) {
        $ms = 1;
    }

    return "{$ms}ms";
}

$_zeroObject = new \stdClass();

function _enc_obj_if_valued(\stdClass &$out, string $field, \JsonSerializable $obj): void
{
    global $_zeroObject;
    $val = $obj->jsonSerialize();
    if ($val != $_zeroObject) {
        $out->{$field} = $val;
    }
}
