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
use DCarbone\PHPConsulAPI\ACL\ACLNodeIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLServiceIdentity;
use DCarbone\PHPConsulAPI\ACL\ACLToken;
use DCarbone\PHPConsulAPI\ACL\ACLTokenPolicyLink;
use DCarbone\PHPConsulAPI\ACL\ACLTokenRoleLink;
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

    public const FIELD_TYPE       = 0;
    public const FIELD_CLASS      = 1;
    public const FIELD_ARRAY_TYPE = 2;
    public const FIELD_CALLBACK   = 3;
    public const FIELD_NULLABLE   = 4;

    public const CALLABLE_HYDRATE_TIME              = [self::class, 'hydrateTime'];
    public const CALLABLE_HYDRATE_NULLABLE_TIME     = [self::class, 'hydrateNullableTime'];
    public const CALLABLE_HYDRATE_DURATION          = [self::class, 'hydrateDuration'];
    public const CALLABLE_HYDRATE_NULLABLE_DURATION = [self::class, 'hydrateNullableDuration'];

    public const COMPLEX_TYPES = [
        ACLToken::class               => [
            'Policies'          => [
                self::FIELD_TYPE       => self::ARRAY,
                self::FIELD_CLASS      => ACLTokenPolicyLink::class,
                self::FIELD_ARRAY_TYPE => self::OBJECT,
            ],
            'Roles'             => [
                self::FIELD_TYPE       => self::ARRAY,
                self::FIELD_CLASS      => ACLTokenRoleLink::class,
                self::FIELD_ARRAY_TYPE => self::OBJECT,
            ],
            'ServiceIdentities' => [
                self::FIELD_TYPE       => self::ARRAY,
                self::FIELD_CLASS      => ACLServiceIdentity::class,
                self::FIELD_ARRAY_TYPE => self::OBJECT,
            ],
            'NodeIdentities'    => [
                self::FIELD_TYPE       => self::ARRAY,
                self::FIELD_CLASS      => ACLNodeIdentity::class,
                self::FIELD_ARRAY_TYPE => self::OBJECT,
            ],
            'AuthMethod'        => [
                self::FIELD_TYPE     => self::STRING,
                self::FIELD_NULLABLE => true,
            ],
            'ExpirationTTL'     => [
                self::FIELD_CALLBACK => self::CALLABLE_HYDRATE_DURATION,
            ],
            'ExpirationTime'    => [
                self::FIELD_CALLBACK => self::CALLABLE_HYDRATE_NULLABLE_TIME,
                self::FIELD_NULLABLE => true,
            ],
            'CreateTime'        => [
                self::FIELD_CALLBACK => self::CALLABLE_HYDRATE_TIME,
            ],
            'Rules'             => [
                self::FIELD_TYPE     => self::STRING,
                self::FIELD_NULLABLE => true,
            ],
            'Namespace'         => [
                self::FIELD_TYPE     => self::STRING,
                self::FIELD_NULLABLE => true,
            ],
        ],
        AgentServiceChecksInfo::class => [
            'Service' => [
                self::FIELD_TYPE  => self::OBJECT,
                self::FIELD_CLASS => AgentService::class,
            ],
            'Checks'  => [
                self::FIELD_TYPE  => self::OBJECT,
                self::FIELD_CLASS => HealthChecks::class,
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
     * @param \DCarbone\Go\Time\Time|string $value
     * @throws \Exception
     */
    public static function hydrateTime(object $instance, string $field, $value): void
    {
        if ($value instanceof Time\Time) {
            $instance->{$field} = clone $value;
            return;
        }
        $instance->{$field} = Time\Time::createFromFormat(Time\Time::DefaultFormat, $value);
    }

    /**
     * @param object $instance
     * @param string $field
     * @param \DCarbone\Go\Time\Time|string|null $value
     * @throws \Exception
     */
    public static function hydrateNullableTime(object $instance, string $field, $value): void
    {
        if (null === $value) {
            $instance->{$field} = null;
            return;
        }
        self::hydrateTime($instance, $field, $value);
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

    /**
     * @param object $instance
     * @param string $field
     * @param mixed $value
     */
    public static function hydrateNullableDuration(object $instance, string $field, $value): void
    {
        if (null === $value) {
            $instance->{$field} = null;
            return;
        }
        self::hydrateDuration($instance, $field, $value);
    }
}
