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

use DCarbone\PHPConsulAPI\AbstractModel;

class HTTPHeaderModifiers extends AbstractModel
{
    /** @var array<string,string> */
    public array $Add;
    /** @var array<string,string> */
    public array $Set;
    /** @var array<string> */
    public array $Remove;

    /**
     * @param array<string,string> $Add
     * @param array<string,string> $Set
     * @param array<string> $Remove
     */
    public function __construct(
        null|array|\stdClass $Add = null,
        null|array|\stdClass $Set = null,
        array $Remove = []
    ) {
        $this->setAdd($Add);
        $this->setSet($Set);
        $this->setRemove(...$Remove);
    }

    /**
     * @return array<string,string>
     */
    public function getAdd(): array
    {
        return $this->Add;
    }

    /**
     * @param null|\stdClass|array<string,string> $Add
     * @return $this
     */
    public function setAdd(null|\stdClass|array $Add): self
    {
        $this->Add = [];
        if (null !== $Add) {
            foreach ($Add as $k => $v) {
                $this->Add[$k] = $v;
            }
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

    /**
     * @param null|\stdClass|array<string,string> $Set
     * @return $this
     */
    public function setSet(null|\stdClass|array $Set): self
    {
        $this->Set = [];
        if (null !== $Set) {
            foreach ($Set as $k => $v) {
                $this->Set[$k] = $v;
            }
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
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if ([] !== $this->Add) {
            $out->Add = $this->Add;
        }
        if ([] !== $this->Set) {
            $out->Set = $this->Set;
        }
        if ([] !== $this->Remove) {
            $out->Remove = $this->Remove;
        }
        return $out;
    }
}
