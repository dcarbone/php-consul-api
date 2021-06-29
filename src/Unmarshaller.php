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

use DCarbone\PHPConsulAPI\Event\UserEvent;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\KV\KVTxnOp;

/**
 * Used to assist with unmarshalling json responses
 *
 * Trait Unmarshaller
 */
trait Unmarshaller
{
    /**
     * Attempts to unmarshal the provided value into the provided field on the implementing class
     *
     * @param string $field
     * @param mixed $value
     */
    protected function unmarshalField(string $field, $value): void
    {
        if (isset(static::FIELDS[$field])) {
            // if the implementing class has some explicitly defined overrides
            $this->unmarshalComplex($field, $value, static::FIELDS[$field]);
        } elseif (!\property_exists($this, $field)) {
            // if the field isn't explicitly defined on the implementing class, just set it to whatever the incoming
            // value is
            $this->{$field} = $value;
        } /** @noinspection PhpStatementHasEmptyBodyInspection */ elseif (null === $value) {
            // if the value is null at this point, ignore and move on.
            // note: this is not checked prior to the property_exists call as if the field is not explicitly defined but
            // is seen with a null value, we still want to define it as null on the implementing type.
        } elseif (isset($this->{$field}) && \is_scalar($this->{$field})) {
            // if the property has a scalar default value, unmarshal it as such.
            $this->unmarshalScalar($field, $value, false);
        } else {
            // if we fall down here, try to set the value as-is.  if this barfs, it indicates we have a bug to be
            // squished.
            // todo: should this be an exception?
            $this->{$field} = $value;
        }
    }

    /**
     * @param array $fieldDef
     * @return bool
     */
    protected function fieldIsNullable(array $fieldDef): bool
    {
        // todo: make sure this key is always a bool...
        return $fieldDef[Transcoding::FIELD_NULLABLE] ?? false;
    }

    /**
     * @param string $type
     * @return false|float|int|string|null
     */
    protected static function scalarZeroVal(string $type)
    {
        if (Transcoding::STRING === $type) {
            return '';
        }
        if (Transcoding::INTEGER === $type) {
            return 0;
        }
        if (Transcoding::DOUBLE === $type) {
            return 0.0;
        }
        if (Transcoding::BOOLEAN === $type) {
            return false;
        }

        return null;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param string $type
     * @param bool $nullable
     * @return bool|float|int|string
     */
    private function buildScalarValue(string $field, $value, string $type, bool $nullable)
    {
        // if the incoming value is null...
        if (null === $value) {
            // ...and this field is nullable, just return null
            if ($nullable) {
                return null;
            }
            // otherwise return zero val for this type
            return self::scalarZeroVal($type);
        }

        if (Transcoding::STRING === $type) {
            return (string)$value;
        }
        if (Transcoding::INTEGER === $type) {
            return \intval($value, 10);
        }
        if (Transcoding::DOUBLE === $type) {
            return (float)$value;
        }
        if (Transcoding::BOOLEAN === $type) {
            return (bool)$value;
        }

        // if we fall down to here, default to try to set the value to whatever it happens to be.
        return $value;
    }

    /**
     * @param string $field
     * @param array|object $value
     * @param string $class
     * @param bool $nullable
     * @return object|null
     */
    private function buildObjectValue(string $field, $value, string $class, bool $nullable): ?object
    {
        // if the incoming value is null...
        if (null === $value) {
            // ...and this field is nullable, return null
            if ($nullable) {
                return null;
            }
            // .. and this field must be an instance of the provided class, return empty new empty instance
            return new $class([]);
        }
        // if the incoming value is already an instance of the class, clone it and return
        if ($value instanceof $class) {
            return clone $value;
        }
        // otherwise, attempt to cast whatever was provided as an array and construct a new instance of $class
        if (KVPair::class === $class || KVTxnOp::class === $class || UserEvent::class === $class) {
            // special case for KVPair and KVTxnOp
            // todo: find cleaner way to do this...
            return new $class((array)$value, true);
        }
        return new $class((array)$value);
    }

    /**
     * Handles scalar type field hydration
     *
     * @param string $field
     * @param mixed $value
     * @param bool $nullable
     */
    private function unmarshalScalar(string $field, $value, bool $nullable): void
    {
        $this->{$field} = $this->buildScalarValue(
            $field,
            $value,
            isset($this->{$field}) ? \gettype($this->{$field}) : Transcoding::MIXED,
            $nullable
        );
    }

    /**
     * Handles complex type field hydration
     *
     * @param string $field
     * @param mixed $value
     * @param array $def
     */
    private function unmarshalComplex(string $field, $value, array $def): void
    {
        // check if a callable has been defined
        if (isset($def[Transcoding::FIELD_UNMARSHAL_CALLBACK])) {
            $cb = $def[Transcoding::FIELD_UNMARSHAL_CALLBACK];
            // allow for using a "setter" method
            if (\is_string($cb) && \method_exists($this, $cb)) {
                $this->{$cb}($value);
                return;
            }
            // handle all other callable input
            $err = \call_user_func($def[Transcoding::FIELD_UNMARSHAL_CALLBACK], $this, $field, $value);
            if (false === $err) {
                throw new \RuntimeException(
                    \sprintf(
                        'Error calling hydration callback "%s" for field "%s" on class "%s"',
                        \var_export($def[Transcoding::FIELD_UNMARSHAL_CALLBACK], true),
                        $field,
                        \get_class($this)
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
        } elseif (isset($this->{$field})) {
            // if the field is set and non-null
            $fieldType = \gettype($this->{$field});
        } else {
            throw new \LogicException(
                \sprintf(
                    'Field "%s" on type "%s" is missing a FIELD_TYPE hydration entry: %s',
                    $field,
                    \get_class($this),
                    \var_export($def, true)
                )
            );
        }

        if (Transcoding::OBJECT === $fieldType) {
            $this->unmarshalObject($field, $value, $def);
            return;
        }

        if (Transcoding::ARRAY === $fieldType) {
            $this->unmarshalArray($field, $value, $def);
            return;
        }

        // at this point, assume scalar
        // todo: handle non-scalar types here
        $this->unmarshalScalar($field, $value, self::fieldIsNullable($def));
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param array $def
     */
    private function unmarshalObject(string $field, $value, array $def): void
    {
        if (!isset($def[Transcoding::FIELD_CLASS])) {
            throw new \LogicException(
                \sprintf(
                    'Field "%s" on type "%s" is missing FIELD_CLASS hydration entry: %s',
                    $field,
                    \get_class($this),
                    \var_export($def, true)
                )
            );
        }

        $this->{$field} = $this->buildObjectValue(
            $field,
            $value,
            $def[Transcoding::FIELD_CLASS],
            self::fieldIsNullable($def)
        );
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param array $def
     */
    private function unmarshalArray(string $field, $value, array $def): void
    {
        // attempt to extract the two possible keys
        $type  = $def[Transcoding::FIELD_ARRAY_TYPE] ?? null;
        $class = $def[Transcoding::FIELD_CLASS]      ?? null;

        // type is required
        if (null === $type) {
            throw new \DomainException(
                \sprintf(
                    'Field "%s" on type "%s" definition is missing FIELD_ARRAY_TYPE value: %s',
                    $field,
                    \get_class($this),
                    \var_export($def, true)
                )
            );
        }

        // is the incoming value null?
        if (null === $value) {
            // if this value can be null'd, allow it.
            if (static::fieldIsNullable($def)) {
                $this->{$field} = null;
            }
            return;
        }

        // by the time we get here, $value must be an array
        if (!\is_array($value)) {
            throw new \RuntimeException(
                \sprintf(
                    'Field "%s" on type "%s" is an array but provided value is "%s"',
                    $field,
                    \get_class($this),
                    \gettype($value)
                )
            );
        }

        // currently the only supported array types are scalar or objects.  everything else will require
        // a custom callback for hydration purposes.

        if (Transcoding::OBJECT === $type) {
            if (null === $class) {
                throw new \DomainException(
                    \sprintf(
                        'Field "%s" on type "%s" definition is missing FIELD_CLASS value: %s',
                        $field,
                        \get_class($this),
                        \var_export($def, true)
                    )
                );
            }

            foreach ($value as $k => $v) {
                // todo: causes double-checking for null if value isn't null, not great...
                if (null === $v) {
                    $this->{$field}[$k] = null;
                } else {
                    $this->{$field}[$k] = $this->buildObjectValue($field, $v, $class, false);
                }
            }
        } else {
            // in all other cases, just set as-is
            foreach ($value as $k => $v) {
                if (null === $v) {
                    $this->{$field}[$k] = null;
                } else {
                    $this->{$field}[$k] = $this->buildScalarValue($field, $v, $type, false);
                }
            }
        }
    }
}
