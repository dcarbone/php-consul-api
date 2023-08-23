<?php

declare(strict_types=1);

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

/**
 * Class AbstractModel
 */
abstract class AbstractModel implements \JsonSerializable
{
    use Marshaller;
    use Unmarshaller;

    protected const FIELDS = [];

    /**
     * Stores dynamically assigned variables
     * @var array
     */
    private array $_dyn = [];

    /**
     * AbstractModel constructor.
     *
     * Convenience method to help set scalar types.  Any extending class must have a constructor that builds any
     * array / object properties it may have.
     *
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        // fast path for "empty"
        if (null === $data || [] === $data) {
            return;
        }
        foreach ($data as $field => $value) {
            $this->unmarshalField($field, $value);
        }
    }

    public function __set(string $field, $value): void
    {
        $this->_dyn[$field] = $value;
    }

    public function __get(string $field)
    {
        return $this->_dyn[$field] ?? null;
    }

    /**
     * todo: this picks up non-public fields.  externalize this at some point.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $out = [];
        // marshal fields
        foreach ((array)$this as $field => $value) {
            // marshal dynamically defined fields
            // todo: this is crap.
            if (substr($field, -4) === '_dyn') {
                if ([] !== $value) {
                    foreach ($value as $k => $v) {
                        $this->marshalField($out, $k, $v);
                    }
                }
            } else {
                $this->marshalField($out, $field, $value);
            }
        }
        return $out;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return static::class;
    }
}
