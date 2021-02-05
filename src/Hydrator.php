<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Used to assist with hydrating json responses
 *
 * Trait Hydrator
 */
trait Hydrator
{
    /**
     * Overload the definition of this array in implementing classes to define custom per-field hydration behavior
     *
     * @var array[]
     */
    protected static array $fields = [];

    /**
     * Attempts to hydrate the provided value into the provided field on the implementing class
     *
     * @param string $field
     * @param mixed $value
     */
    protected function hydrateField(string $field, $value): void
    {
        // if the implementing class has some explicitly defined overrides
        if (isset(static::$fields[$field])) {
            $this->hydrateComplex($field, $value, static::$fields[$field]);
            return;
        }

        // if the implementing class has an entry in the global registry, use it.
        if (isset(Hydration::COMPLEX_TYPES[self::class], Hydration::COMPLEX_TYPES[self::class][$field])) {
            $this->hydrateComplex($field, $value, Hydration::COMPLEX_TYPES[self::class]);
            return;
        }

        // if the field isn't explicitly defined on the implementing class, just set it to whatever the incoming
        // value is
        if (!\property_exists($this, $field)) {
            $this->{$field} = $value;
            return;
        }

        // if the value is null at this point, ignore and move on.
        // note: this is not checked prior to the property_exists call as if the field is not explicitly defined but
        // is seen with a null value, we still want to define it as null on the implementing type.
        if (null === $value) {
            return;
        }

        // if the property has a scalar default value, hydrate it as such.
        if (\is_scalar($this->{$field})) {
            $this->hydrateScalar($field, $value, false);
            return;
        }

        // if we fall down here, try to set the value as-is.  if this barfs, it indicates we have a bug to be squished.
        // todo: should this be an exception?
        $this->{$field} = $value;
    }

    /**
     * @param string $field
     * @param array $def
     * @return bool
     */
    protected function fieldIsNullable(string $field, array $def): bool
    {
        return !(bool)($def[Hydration::FIELD_NOT_NULLABLE] ?? false);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param string $type
     * @return bool|float|int|string
     */
    private function buildScalar(string $field, $value, string $type)
    {
        if (Hydration::STRING === $type) {
            return (string)$value;
        }
        if (Hydration::INTEGER === $type) {
            return \intval($value, 10);
        }
        if (Hydration::DOUBLE === $type) {
            return (float)$value;
        }
        if (Hydration::BOOLEAN === $type) {
            return (bool)$value;
        }
        throw new \DomainException(
            \sprintf('Unable to handle field "%s" of type "%s" on class "%s"', $field, $type, \get_class($this))
        );
    }

    /**
     * @param string $field
     * @param array|object $value
     * @param string $class
     * @return object
     */
    private function buildObject(string $field, $value, string $class): object
    {
        if ($value instanceof $class) {
            return clone $value;
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
    private function hydrateScalar(string $field, $value, bool $nullable): void
    {
        // if the incoming value is null...
        if (null === $value) {
            if ($nullable) {
                // and this field _is_ nullable, set to null and move on
                $this->{$field} = null;
            }
            // otherwise return without disturbing current default value
            return;
        }
        // else set field to casted scalar
        $this->{$field} = $this->buildScalar($field, $value, \gettype($this->{$field}));
    }

    /**
     * Handles complex type field hydration
     *
     * @param string $field
     * @param mixed $value
     * @param array $def
     */
    private function hydrateComplex(string $field, $value, array $def): void
    {
        // check if a callable has been defined
        if (isset($def[Hydration::FIELD_CALLBACK])) {
            $err = \call_user_func($def[Hydration::FIELD_CALLBACK], $this, $field, $value);
            if (false === $err) {
                throw new \RuntimeException(
                    \sprintf(
                        'Error calling hydration callback "%s" for field "%s" on class "%s"',
                        $def[Hydration::FIELD_CALLBACK],
                        $field,
                        \get_class($this)
                    )
                );
            }
            return;
        }

        // at this point, type and class must be defined

        if (!isset($def[Hydration::FIELD_TYPE])) {
            throw new \LogicException(
                \sprintf(
                    'Field "%s" on type "%s" is missing a FIELD_TYPE hydration entry: %s',
                    $field,
                    \get_class($this),
                    \var_export($def, true)
                )
            );
        }

        $type = $def[Hydration::FIELD_TYPE];

        if (Hydration::OBJECT === $type) {
            $this->hydrateObject($field, $value, $def);
            return;
        }

        if (Hydration::ARRAY === $type) {
            $this->hydrateArray($field, $value, $def);
            return;
        }

        // at this point, assume scalar
        // todo: handle non-scalar types here
        $this->hydrateScalar($field, $value, self::fieldIsNullable($field, $def));
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param array $def
     */
    private function hydrateObject(string $field, $value, array $def): void
    {
        if (!isset($def[Hydration::FIELD_CLASS])) {
            throw new \LogicException(
                \sprintf(
                    'Field "%s" on type "%s" is missing FIELD_CLASS hydration entry: %s',
                    $field,
                    \get_class($this),
                    \var_export($def, true)
                )
            );
        }

        $this->{$field} = $this->buildObject($field, $value, $def[Hydration::FIELD_CLASS]);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param array $def
     */
    private function hydrateArray(string $field, $value, array $def): void
    {
        // attempt to extract the two possible keys
        $type = $def[Hydration::FIELD_ARRAY_TYPE] ?? null;
        $class = $def[Hydration::FIELD_CLASS] ?? null;

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
            if (static::fieldIsNullable($field, $def)) {
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

        if (Hydration::OBJECT === $type) {
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

            foreach ($value as $v) {
                $this->{$field}[] = $this->buildObject($field, $v, $class);
            }
        } else {
            // in all other cases, just set as-is
            $this->{$field} = $value;
        }
    }
}
