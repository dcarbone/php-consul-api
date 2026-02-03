<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class HTTPHeaderModifiers extends AbstractType
{
    /** @var array<string,string> */
    public array $Add;
    /** @var array<string,string> */
    public array $Set;
    /** @var array<string> */
    public array $Remove;

    /**
     * @param null|array<string,string> $Add
     * @param null|array<string,string> $Set
     * @param null|array<string> $Remove
     */
    public function __construct(
        null|array $Add = null,
        null|array $Set = null,
        null|array $Remove = null,
    ) {
        $this->setAdd($Add);
        $this->setSet($Set);
        if (null !== $Remove) {
            $this->setRemove(...$Remove);
        }
    }

    /**
     * @return array<string,string>
     */
    public function getAdd(): array
    {
        return $this->Add;
    }

    public function setAddKey(string $k, string $v): self
    {
        if (!isset($this->Add)) {
            $this->Add = [];
        }
        $this->Add[$k] = $v;
        return $this;
    }

    /**
     * @param null|\stdClass|array<string,string> $Add
     */
    public function setAdd(null|\stdClass|array $Add): self
    {
        unset($this->Add);
        if (null === $Add) {
            return $this;
        }
        foreach ($Add as $k => $v) {
            $this->setAddKey($k, $v);
        }
        return $this;
    }

    /**
     * @return array<string,string>
     */
    public function getSet(): array
    {
        return $this->Set;
    }

    public function setSetKey(string $k, string $v): self
    {
        if (!isset($this->Set)) {
            $this->Set = [];
        }
        $this->Set[$k] = $v;
        return $this;
    }

    /**
     * @param null|\stdClass|array<string,string> $Set
     */
    public function setSet(null|\stdClass|array $Set): self
    {
        unset($this->Set);
        if (null === $Set) {
            return $this;
        }
        foreach ($Set as $k => $v) {
            $this->setSetKey($k, $v);
        }
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getRemove(): array
    {
        return $this->Remove;
    }

    public function setRemove(string ...$Remove): self
    {
        $this->Remove = $Remove;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Set' === $k) {
                $n->setSet($v);
            } elseif ('Add' === $k) {
                $n->setAdd($v);
            } elseif ('Remove' === $k) {
                if (null !== $v) {
                    $n->setRemove(...$v);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (isset($this->Add)) {
            $out->Add = $this->Add;
        }
        if (isset($this->Set)) {
            $out->Set = $this->Set;
        }
        if (isset($this->Remove)) {
            $out->Remove = $this->Remove;
        }
        return $out;
    }
}
