<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class Transcoding
 */
final class Transcoding
{
    public const STRING   = 'string';
    public const INTEGER  = 'integer';
    public const DOUBLE   = 'double';
    public const BOOLEAN  = 'boolean';
    public const OBJECT   = 'object';
    public const ARRAY    = 'array';
    public const RESOURCE = 'resource';
    public const MIXED    = 'mixed';
    public const NULL     = 'NULL';

    public const SCALAR = [self::STRING, self::INTEGER, self::DOUBLE, self::BOOLEAN];

    public const TRUE  = 'true';
    public const FALSE = 'false';

    public const FIELD_TYPE               = 0;
    public const FIELD_CLASS              = 1;
    public const FIELD_ARRAY_TYPE         = 2;
    public const FIELD_UNMARSHAL_CALLBACK = 3;
    public const FIELD_NULLABLE           = 4;
    public const FIELD_OMITEMPTY          = 5;
    public const FIELD_SKIP               = 6;
    public const FIELD_MARSHAL_AS         = 7;
    public const FIELD_UNMARSHAL_AS       = 8;

    public const FIELD_QUERY_META = 'QueryMeta';
    public const FIELD_WRITE_META = 'WriteMeta';
    public const FIELD_ERR        = 'Err';

    public const UNMARSHAL_TIME              = [self::class, 'unmarshalTime'];
    public const UNMARSHAL_NULLABLE_TIME     = [self::class, 'unmarshalNullableTime'];
    public const UNMARSHAL_DURATION          = [self::class, 'unmarshalDuration'];
    public const UNMARSHAL_NULLABLE_DURATION = [self::class, 'unmarshalNullableDuration'];

    //-- common field type definitions

    public const MAP_FIELD      = [self::FIELD_TYPE => self::OBJECT, self::FIELD_CLASS => FakeMap::class];
    public const DURATION_FIELD = [
        self::FIELD_TYPE  => self::OBJECT,
        self::FIELD_CLASS => Time\Duration::class,
    ] + self::UNMARSHAL_DURATION;

    //-- common field type definitions with omitempty

    public const OMITEMPTY_FIELD = [self::FIELD_OMITEMPTY => true];

    public const OMITEMPTY_STRING_FIELD       = [self::FIELD_TYPE => self::STRING]  + self::OMITEMPTY_FIELD;
    public const OMITEMPTY_INTEGER_FIELD      = [self::FIELD_TYPE => self::INTEGER] + self::OMITEMPTY_FIELD;
    public const OMITEMPTY_DOUBLE_FIELD       = [self::FIELD_TYPE => self::DOUBLE]  + self::OMITEMPTY_FIELD;
    public const OMITEMPTY_BOOLEAN_FIELD      = [self::FIELD_TYPE => self::BOOLEAN] + self::OMITEMPTY_FIELD;
    public const OMITEMPTY_STRING_ARRAY_FIELD = [
        self::FIELD_TYPE       => self::ARRAY,
        self::FIELD_ARRAY_TYPE => self::STRING,
    ] + self::OMITEMPTY_FIELD;
    public const OMITEMPTY_INTEGER_ARRAY_FIELD = [
        self::FIELD_TYPE       => self::ARRAY,
        self::FIELD_ARRAY_TYPE => self::INTEGER,
    ]                                                  + self::OMITEMPTY_FIELD;
    public const OMITEMPTY_MAP_FIELD = self::MAP_FIELD + self::OMITEMPTY_FIELD;

    /**
     * @param string $type
     * @return bool
     */
    public static function isScalar(string $type): bool
    {
        return \in_array($type, self::SCALAR, true);
    }

    /**
     * @param object $instance
     * @param string $field
     * @param \DCarbone\Go\Time\Time|string $value
     * @throws \Exception
     */
    public static function unmarshalTime(object $instance, string $field, $value): void
    {
        if ($value instanceof Time\Time) {
            $instance->{$field} = clone $value;
            return;
        }
        $instance->{$field} = Time\Time::createFromFormat(\DATE_RFC3339, $value);
    }

    /**
     * @param object $instance
     * @param string $field
     * @param \DCarbone\Go\Time\Time|string|null $value
     * @throws \Exception
     */
    public static function unmarshalNullableTime(object $instance, string $field, $value): void
    {
        if (null === $value) {
            $instance->{$field} = null;
            return;
        }
        self::unmarshalTime($instance, $field, $value);
    }

    /**
     * This accepts a multitude of $value types.  See Time::Duration for implementation details.
     *
     * @param object $instance
     * @param string $field
     * @param mixed $value
     */
    public static function unmarshalDuration(object $instance, string $field, $value): void
    {
        $instance->{$field} = Time::Duration($value);
    }

    /**
     * @param object $instance
     * @param string $field
     * @param mixed $value
     */
    public static function unmarshalNullableDuration(object $instance, string $field, $value): void
    {
        if (null === $value) {
            $instance->{$field} = null;
            return;
        }
        self::unmarshalDuration($instance, $field, $value);
    }
}
