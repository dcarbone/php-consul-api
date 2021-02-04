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

/**
 * Used to assist with hydrating json responses
 *
 * Trait FieldHydration
 * @package DCarbone\PHPConsulAPI
 */
trait FieldHydration
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
        // if this field requires specific hydration logic as defined by an implementing class
        //
        // else if the field does not exist as a specifically defined property on the implementing class, we'll utilize
        // late static binding to dynamically create the field on the returned type and move on.
        //
        // else if the input value is null, move on.
        //
        // else, determine the default value of the property on the implementing class and attempt hydration
        // into that type
        //
        // scalar types are handled with best-effort value casting
        //
        // fields that require more complex hydration logic must either be defined in the $fields static array or
        // in the Hydration::COMPLEX_FIELDS array.

        if (isset(static::$fields[$field])) {
            $this->hydrateComplex($field, $value, static::$fields[$field]);
            return;
        }

        if (!property_exists($this, $field)) {
            $this->{$field} = $value;
            return;
        }

        if (null === $value) {
            return;
        }

        if (is_scalar($this->{$field})) {
            $this->hydrateSimple($field, $value);
            return;
        }

        if (isset(Hydration::COMPLEX_FIELDS[$field])) {
            $this->hydrateComplex($field, $value, Hydration::COMPLEX_FIELDS[$field]);
            return;
        }

        // todo: should this be an exception?
        $this->{$field} = $value;
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
            return strval($value);
        } elseif (Hydration::INTEGER === $type) {
            return intval($value, 10);
        } elseif (Hydration::DOUBLE === $type) {
            return floatval($value);
        } elseif (Hydration::BOOLEAN === $type) {
            return boolval($value);
        } else {
            throw new \DomainException(
                sprintf('Unable to handle field "%s" of type "%s" on class "%s"', $field, $type, get_class($this))
            );
        }
    }

    /**
     * @param string $field
     * @param object|array $value
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
     * @param $value
     */
    private function hydrateSimple(string $field, $value): void
    {
        $this->{$field} = $this->buildScalar($field, $value, gettype($this->{$field}));
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
            $err = call_user_func($def[Hydration::FIELD_CALLBACK], $this, $field, $value);
            if (false === $err) {
                throw new \RuntimeException(
                    sprintf(
                        'Error calling hydration callback "%s" for field "%s" on class "%s"',
                        $def[Hydration::FIELD_CALLBACK],
                        $field,
                        get_class($this)
                    )
                );
            }
            return;
        }

        // at this point, type and class must be defined

        if (!isset($def[Hydration::FIELD_TYPE])) {
            throw new \LogicException(
                sprintf(
                    'Field "%s" on type "%s" is missing a FIELD_TYPE hydration entry: %s',
                    $field,
                    get_class($this),
                    var_export($def, true)
                )
            );
        }

        $type = $def[Hydration::FIELD_TYPE];

        if (Hydration::OBJECT === $type) {
            $this->hydrateObject($field, $value, $def);
        } elseif (Hydration::ARRAY === $type) {
            $this->hydrateArray($field, $value, $def);
        } else {
            throw new \DomainException(
                sprintf(
                    'Unable to handle complex field "%s" of type "%s" on class "%s": %s',
                    $field,
                    $type,
                    get_class($this),
                    var_export($def, true)
                )
            );
        }
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
                sprintf(
                    'Field "%s" on type "%s" is missing FIELD_CLASS hydration entry: %s',
                    $field,
                    get_class($this),
                    var_export($def, true)
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
        // by the time we get here, $value must be an array
        if (!is_array($value)) {
            throw new \RuntimeException(
                sprintf(
                    'Field "%s" on type "%s" is an array but provided value is "%s"',
                    $field,
                    get_class($this),
                    gettype($value)
                )
            );
        }

        // attempt to extract the two possible keys
        $type = $def[Hydration::FIELD_ARRAY_TYPE] ?? null;
        $class = $def[Hydration::FIELD_CLASS] ?? null;

        // type is required
        if (null === $type) {
            throw new \DomainException(
                sprintf(
                    'Field "%s" on type "%s" definition is missing FIELD_ARRAY_TYPE value: %s',
                    $field,
                    get_class($this),
                    var_export($def, true)
                )
            );
        }

        // currently the only supported array types are scalar or objects.  everything else will require
        // a custom callback for hydration purposes.

        if (Hydration::OBJECT === $type) {
            if (null === $class) {
                throw new \DomainException(
                    sprintf(
                        'Field "%s" on type "%s" definition is missing FIELD_CLASS value: %s',
                        $field,
                        get_class($this),
                        var_export($def, true)
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