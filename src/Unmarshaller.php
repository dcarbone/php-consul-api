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

use DCarbone\PHPConsulAPI\Event\UserEvent;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\KV\KVTxnOp;

abstract class Unmarshaller
{
    public static function unmarshalJSON(mixed $decoded, AbstractModel $model): void
    {
        if (null === $decoded) {
            return;
        }
        if (is_scalar($decoded)) {

        }
        if (isset($model::FIELDS[$field])) {
            // if the implementing class has some explicitly defined overrides
            self::unmarshalComplex(model: $model, field: $field, value: $value, def: $model::FIELDS[$field]);
        } elseif (!property_exists($model, $field)) {
            // if the field isn't explicitly defined on the implementing class, just set it to whatever the incoming
            // value is
            $model->{$field} = $value;
        } elseif (null !== $value) {
            // at this point, value must be non-null to be operable
            if (isset($model->{$field}) && is_scalar($model->{$field})) {
                // if the property has a scalar default value, unmarshal it as such.
                self::unmarshalScalar(model: $model, field: $field, value: $value, nullable: false);
            } else {
                // if we fall down here, try to set the value as-is.  if this barfs, it indicates we have a bug to be
                // squished.
                // todo: should this be an exception?
                $model->{$field} = $value;
            }
        }
        // if the value is null at this point, ignore and move on.
        // note: this is not checked prior to the property_exists call as if the field is not explicitly defined but
        // is seen with a null value, we still want to define it as null on the implementing type.
    }

    protected static function fieldIsNullable(array $fieldDef): bool
    {
        // todo: make sure this key is always a bool...
        return $fieldDef[Transcoding::FIELD_NULLABLE] ?? false;
    }

    protected static function scalarZeroVal(string $type): float|bool|int|string|null
    {
        return match ($type) {
            Transcoding::STRING => '',
            Transcoding::INTEGER => 0,
            Transcoding::DOUBLE => 0.0,
            Transcoding::BOOLEAN => false,
            default => null,
        };
    }

    private static function buildScalarValue(mixed $value, string $type, bool $nullable): float|bool|int|string|null
    {
        if (null === $value) {
            return $nullable ? null : self::scalarZeroVal(type: $type);
        }

        return match ($type) {
            Transcoding::STRING => (string)$value,
            Transcoding::INTEGER => (int)$value,
            Transcoding::DOUBLE => (float)$value,
            Transcoding::BOOLEAN => (bool)$value,

            // if we fall down to here, default to try to set the value to whatever it happens to be.
            default => $value,
        };
    }

    private static function buildObjectValue(null|object|array $value, string $class, bool $nullable): ?object
    {
        // if the incoming value is null...
        if (null === $value) {
            return $nullable ? null : new $class();
        }
        // if the incoming value is already an instance of the class, clone it and return
        if ($value instanceof $class) {
            return clone $value;
        }
        // otherwise, attempt to cast whatever was provided as an array and construct a new instance of $class
        if (KVPair::class === $class || KVTxnOp::class === $class || UserEvent::class === $class) {
            // special case for KVPair and KVTxnOp
            // todo: find cleaner way to do this...
            $inst = new $class();
            self::unmarshalJSON();
            return new $class((array)$value, true);
        }
        return new $class((array)$value);
    }

    private static function unmarshalScalar(AbstractModel $model, string $field, mixed $value, bool $nullable): void
    {
        $model->{$field} = self::buildScalarValue(
            value: $value,
            type: isset($model->{$field}) ? \gettype($model->{$field}) : Transcoding::MIXED,
            nullable: $nullable
        );
    }

    private static function unmarshalComplex(AbstractModel $model, string $field, mixed $value, array $def): void
    {
        // check if a callable has been defined
        if (isset($def[Transcoding::FIELD_UNMARSHAL_CALLBACK])) {
            $cb = $def[Transcoding::FIELD_UNMARSHAL_CALLBACK];
            // allow for using a "setter" method
            if (\is_string($cb) && method_exists($model, $cb)) {
                $model->{$cb}($value);
                return;
            }
            // handle all other callable input
            $err = \call_user_func($def[Transcoding::FIELD_UNMARSHAL_CALLBACK], $model, $field, $value);
            if (false === $err) {
                throw new \RuntimeException(
                    sprintf(
                        'Error calling hydration callback "%s" for field "%s" on class "%s"',
                        var_export($def[Transcoding::FIELD_UNMARSHAL_CALLBACK], true),
                        $field,
                        $model::class
                    )
                );
            }
            return;
        }

        // try to determine field type by first looking up the field in the definition map, then by inspecting the
        // the field's default value.
        //
        // objects _must_ have an entry in the map, as they are either un-initialized at class instantiation time or
        // set to "NULL", at which point we cannot automatically determine the value type.

        if (isset($def[Transcoding::FIELD_TYPE])) {
            // if the field has a FIELD_TYPE value in the definition map
            $fieldType = $def[Transcoding::FIELD_TYPE];
        } elseif (isset($model->{$field})) {
            // if the field is set and non-null
            $fieldType = \gettype($model->{$field});
        } else {
            throw new \LogicException(
                sprintf(
                    'Field "%s" on type "%s" is missing a FIELD_TYPE hydration entry: %s',
                    $field,
                    $model::class,
                    var_export($def, true)
                )
            );
        }

        if (Transcoding::OBJECT === $fieldType) {
            self::unmarshalObject($model, $field, $value, $def);
            return;
        }

        if (Transcoding::ARRAY === $fieldType) {
            self::unmarshalArray($model, $field, $value, $def);
            return;
        }

        // at this point, assume scalar
        // todo: handle non-scalar types here
        self::unmarshalScalar($model, $field, $value, self::fieldIsNullable($def));
    }

    private static function unmarshalObject(AbstractModel $model, string $field, mixed $value, array $def): void
    {
        if (!isset($def[Transcoding::FIELD_CLASS])) {
            throw new \LogicException(
                sprintf(
                    'Field "%s" on type "%s" is missing FIELD_CLASS hydration entry: %s',
                    $field,
                    $model::class,
                    var_export($def, true)
                )
            );
        }

        $model->{$field} = self::buildObjectValue(
            $value,
            $def[Transcoding::FIELD_CLASS],
            self::fieldIsNullable($def)
        );
    }

    private static function unmarshalArray(AbstractModel $model, string $field, mixed $value, array $def): void
    {
        // attempt to extract the two possible keys
        $type  = $def[Transcoding::FIELD_ARRAY_TYPE] ?? null;
        $class = $def[Transcoding::FIELD_CLASS]      ?? null;

        // type is required
        if (null === $type) {
            throw new \DomainException(
                sprintf(
                    'Field "%s" on type "%s" definition is missing FIELD_ARRAY_TYPE value: %s',
                    $field,
                    $model::class,
                    var_export($def, true)
                )
            );
        }

        // is the incoming value null?
        if (null === $value) {
            // if this value can be null'd, allow it.
            if (self::fieldIsNullable($def)) {
                $model->{$field} = null;
            }
            return;
        }

        // by the time we get here, $value must be an array
        if (!\is_array($value)) {
            throw new \RuntimeException(
                sprintf(
                    'Field "%s" on type "%s" is an array but provided value is "%s"',
                    $field,
                    $model::class,
                    \gettype($value)
                )
            );
        }

        // currently the only supported array types are scalar or objects.  everything else will require
        // a custom callback for hydration purposes.

        if (Transcoding::OBJECT === $type) {
            if (null === $class) {
                throw new \DomainException(
                    sprintf(
                        'Field "%s" on type "%s" definition is missing FIELD_CLASS value: %s',
                        $field,
                        $model::class,
                        var_export($def, true)
                    )
                );
            }

            foreach ($value as $k => $v) {
                // short circuit to prevent additional func call
                if (null === $v) {
                    $model->{$field}[$k] = null;
                } else {
                    $model->{$field}[$k] = self::buildObjectValue($v, $class, false);
                }
            }
        } else {
            // in all other cases, just set as-is
            foreach ($value as $k => $v) {
                // short circuit to prevent additional func call
                if (null === $v) {
                    $model->{$field}[$k] = null;
                } else {
                    $model->{$field}[$k] = self::buildScalarValue($v, $type, false);
                }
            }
        }
    }
}
