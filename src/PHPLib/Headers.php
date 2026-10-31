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

class Headers extends Values
{
    private function _canonicalizeHeaderKey(string $key): string {
        return str_replace(' ', '-', ucwords(str_replace(['-', '_'], ' ', strtolower($key))));
    }

    protected function _get(string $key): null|string
    {
        $key = $this->_canonicalizeHeaderKey($key);
        if (isset($this->values[$key]) && count($this->values[$key]) > 0) {
            return $this->values[$key][0];
        }
        return null;
    }

    /**
     * @return array<string,<array<string>>
     */
    protected function _getAll(string $key): array
    {
        return $this->values[$this->_canonicalizeHeaderKey($key)] ?? [];
    }

    protected function _set(string $key, string ...$value): void
    {
        $this->values[$this->_canonicalizeHeaderKey($key)] = $value;
    }

    protected function _add(string $key, string ...$value): void
    {
        $key = $this->_canonicalizeHeaderKey($key);
        if (isset($this->values[$key])) {
            $this->values[$key] = array_merge($this->values[$key], $value);
        } else {
            $this->values[$key] = $value;
        }
    }

    protected function _delete(string $key): void
    {
        unset($this->values[$this->_canonicalizeHeaderKey($key)]);
    }

    /**
     * @return array<string,array<string>>
     */
    public function toPsr7Array(): array
    {
        return $this->values;
    }
}
