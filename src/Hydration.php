<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Agent\AgentServiceChecksInfo;
use DCarbone\PHPConsulAPI\Health\HealthChecks;

/**
 * Class Hydration
 */
final class Hydration
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

    public const FIELD_TYPE         = 0;
    public const FIELD_CLASS        = 1;
    public const FIELD_ARRAY_TYPE   = 2;
    public const FIELD_CALLBACK     = 3;
    public const FIELD_NOT_NULLABLE = 4;

    public const CALLABLE_HYDRATE_TIME     = [self::class, 'hydrateTime'];
    public const CALLABLE_HYDRATE_DURATION = [self::class, 'hydrateDuration'];

    public const COMPLEX_TYPES = [
        AgentServiceChecksInfo::class => [
            'Service' => [
                self::FIELD_TYPE  => self::OBJECT,
                self::FIELD_CLASS => AgentService::class,
            ],
            'Checks'  => [
                self::FIELD_TYPE         => self::OBJECT,
                self::FIELD_CLASS        => HealthChecks::class,
                self::FIELD_NOT_NULLABLE => true,
            ],
        ],
    ];

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
     * @param string $value
     * @throws \Exception
     */
    public static function hydrateTime(object $instance, string $field, string $value): void
    {
        $instance->{$field} = Time\Time::createFromFormat(Time\Time::DefaultFormat, $value);
    }

    /**
     * @param object $instance
     * @param string $field
     * @param mixed $value
     */
    public static function hydrateDuration(object $instance, string $field, $value): void
    {
        $instance->{$field} = Time::Duration($value);
    }
}
