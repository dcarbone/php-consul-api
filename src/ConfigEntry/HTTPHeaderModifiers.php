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

use function DCarbone\PHPConsulAPI\_enc_obj_if_valued;

class HTTPHeaderModifiers extends AbstractModel
{
    public \stdClass $Add;
    public \stdClass $Set;
    /** @var array<string> */
    public array $Remove;

    /**
     * @param array<string> $Remove
     */
    public function __construct(
        null|\stdClass $Add = null,
        null|\stdClass $Set = null,
        array $Remove = []
    ) {
        $this->Add = $Add ?? new \stdClass();
        $this->Set = $Set ?? new \stdClass();
        $this->setRemove(...$Remove);
    }

    public function getAdd(): \stdClass
    {
        return $this->Add;
    }

    public function setAddKey(string $key, string $value): self
    {
        $this->Add->{$key} = $value;
        return $this;
    }

    public function setAdd(\stdClass $Add): self
    {
        foreach ($Add as $k => $v) {
            $this->setaddKey($k, $v);
        }
        return $this;
    }

    public function getSet(): \stdClass
    {
        return $this->Set;
    }

    public function setSetKey(string $key, string $value): self
    {
        $this->Set->{$key} = $value;
        return $this;
    }

    public function setSet(\stdClass $Set): self
    {
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
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        _enc_obj_if_valued($out, 'Add', $this->Add);
        _enc_obj_if_valued($out, 'Set', $this->Set);
        if ([] !== $this->Remove) {
            $out->Remove = $this->Remove;
        }
        return $out;
    }
}
