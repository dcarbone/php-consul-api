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
    /** @var array<string,string> */
    private array $normalizedKeys = [];

    /**
     * @param array<string,array<string>> $values
     */
    public static function fromArray(array $values): self
    {
        $out = new self();
        foreach ($values as $header => $items) {
            $out->add((string)$header, ...(array)$items);
        }
        return $out;
    }

    protected function findKey(string $key): null|string
    {
        $normalizedKey = strtolower($key);
        return $this->normalizedKeys[$normalizedKey] ?? null;
    }

    public function set(string $key, string ...$value): void
    {
        $storedKey = $this->findKey($key);
        parent::set($storedKey ?? $key, ...$value);
        if (null === $storedKey) {
            $this->storeKey($key);
        }
    }

    public function add(string $key, string ...$value): void
    {
        $storedKey = $this->findKey($key);
        parent::add($storedKey ?? $key, ...$value);
        if (null === $storedKey) {
            $this->storeKey($key);
        }
    }

    public function delete(string $key): void
    {
        $storedKey = $this->findKey($key);
        if (null === $storedKey) {
            return;
        }

        parent::delete($storedKey);
        $normalizedKey = strtolower($storedKey);
        unset($this->normalizedKeys[$normalizedKey]);
    }

    private function storeKey(string $key): void
    {
        $this->normalizedKeys[strtolower($key)] = $key;
    }
}
