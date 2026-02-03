<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PHPLib\Types;

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

trait TaggedAddressField
{
    /** @var array<string,string> */
    public array $TaggedAddresses;

    /**
     * @return null|array<string,string>
     */
    public function getTaggedAddresses(): null|array
    {
        return $this->TaggedAddresses ?? null;
    }

    public function setTaggedAddress(string $k, string $v): self
    {
        if (!isset($this->TaggedAddresses)) {
            $this->TaggedAddresses = [];
        }
        $this->TaggedAddresses[$k] = $v;
        return $this;
    }

    /**
     * @param \stdClass|array<string,string>|null $TaggedAddresses
     */
    public function setTaggedAddresses(null|\stdClass|array $TaggedAddresses): self
    {
        unset($this->TaggedAddresses);
        if (null === $TaggedAddresses) {
            return $this;
        }
        foreach ($TaggedAddresses as $k => $v) {
            $this->setTaggedAddress($k, $v);
        }
        return $this;
    }
}
