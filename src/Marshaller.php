<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\Go\Time;

abstract class Marshaller
{
    protected static function marshalField(\stdClass $output, string $field, null|array $def, mixed $value): void
    {
        // if this field has no special handling, set as-is and move on.
        if (null === $def) {
            $output->{$field} = $value;
            return;
        }

        // if this field is marked as being "skipped", do not set, then move on.
        if ($def[Transcoding::FIELD_SKIP] ?? false) {
            return;
        }

        // if this field is marked as needing to be typecast to a specific type for output
        if (isset($def[Transcoding::FIELD_MARSHAL_AS])) {
            $value = match ($def[Transcoding::FIELD_MARSHAL_AS]) {
                Transcoding::STRING => (string)$value,
                Transcoding::INTEGER => (int)$value,
                Transcoding::DOUBLE => (float)$value,
                Transcoding::BOOLEAN => (bool)$value,
                default => throw new \InvalidArgumentException(
                    sprintf('Unable to handle serializing to %s', $def[Transcoding::FIELD_MARSHAL_AS])
                ),
            };
        }

        // if this field is NOT explicitly marked as "omitempty", set and move on.
        if (!isset($def[Transcoding::FIELD_OMITEMPTY]) || true !== $def[Transcoding::FIELD_OMITEMPTY]) {
            $output[$field] = $value;
            return;
        }

        // otherwise, handle value setting on a per-type basis

        $type = \gettype($value);

        // strings must be non empty
        if (Transcoding::STRING === $type) {
            if ('' !== $value) {
                $output->{$field} = $value;
            }
            return;
        }

        // integers must be non-zero (negatives are ok)
        if (Transcoding::INTEGER === $type) {
            if (0 !== $value) {
                $output->{$field} = $value;
            }
            return;
        }

        // floats must be non-zero (negatives are ok)
        if (Transcoding::DOUBLE === $type) {
            if (0.0 !== $value) {
                $output->{$field} = $value;
            }
            return;
        }

        // bools must be true
        if (Transcoding::BOOLEAN === $type) {
            if ($value) {
                $output->{$field} = $value;
            }
            return;
        }

        // object "non-zero" calculations require a bit more finesse...
        if (Transcoding::OBJECT === $type) {
            // AbstractModels are collections, and are non-zero if they contain at least 1 entry
            if ($value instanceof FakeSlice || $value instanceof FakeMap) {
                if (0 < \count($value)) {
                    $output->{$field} = $value;
                }
                return;
            }

            // Time\Duration types are non-zero if their internal value is > 0
            if ($value instanceof Time\Duration) {
                if (0 < $value->Nanoseconds()) {
                    $output->{$field} = $value;
                }
                return;
            }

            // Time\Time values are non-zero if they are anything greater than epoch
            if ($value instanceof Time\Time) {
                if (!$value->IsZero()) {
                    $output->{$field} = $value;
                }
                return;
            }

            // otherwise, by being defined it is non-zero, so add it.
            $output->{$field} = $value;
            return;
        }

        // arrays must have at least 1 value
        if (Transcoding::ARRAY === $type) {
            if ([] !== $value) {
                $output->{$field} = $value;
            }
            return;
        }

        // todo: be more better about resources
        if (Transcoding::RESOURCE === $type) {
            $output->{$field} = $value;
            return;
        }

        // once we get here the only possible value type is "NULL", which are always considered "empty".  thus, do not
        // set any value.
    }

    public static function marshalJSON(object $obj, array $fields, array $dynamic): \stdClass
    {

    }
}
