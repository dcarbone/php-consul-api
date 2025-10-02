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

trait MetaContainer
{
    /** @var array<string,string> */
    public array $Meta;

    /**
     * @return array<string,string>|null
     */
    public function getMeta(): null|array
    {
        return $this->Meta ?? null;
    }

    public function setMetaKey(string $k, string $v): self
    {
        if (!isset($this->Meta)) {
            $this->Meta = [];
        }
        $this->Meta[$k] = $v;
        return $this;
    }

    /**
     * @param null|\stdClass|array<string,string> $Meta
     */
    public function setMeta(null|\stdClass|array $Meta): self
    {
        unset($this->Meta);
        if (null === $Meta) {
            return $this;
        }
        foreach ($Meta as $k => $v) {
            $this->setMetaKey($k, $v);
        }
        return $this;
    }
}
