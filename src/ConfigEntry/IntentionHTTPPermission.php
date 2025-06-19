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

class IntentionHTTPPermission extends AbstractModel
{
    public string $PathExact;
    public string $PathPrefix;
    public string $PathRegex;
    /** @var array<\DCarbone\PHPConsulAPI\ConfigEntry\IntentionHTTPHeaderPermission> */
    public array $Header;
    /** @var array<string> */
    public array $Methods;

    /**
     * @param array<\DCarbone\PHPConsulAPI\ConfigEntry\IntentionHTTPHeaderPermission> $Header
     * @param array<string> $Methods
     */
    public function __construct(
        string $PathExact = '',
        string $PathPrefix = '',
        string $PathRegex = '',
        array $Header = [],
        array $Methods = []
    ) {
        $this->PathExact = $PathExact;
        $this->PathPrefix = $PathPrefix;
        $this->PathRegex = $PathRegex;
        $this->setHeader(...$Header);
        $this->setMethods(...$Methods);
    }

    public function getPathExact(): string
    {
        return $this->PathExact;
    }

    public function setPathExact(string $PathExact): self
    {
        $this->PathExact = $PathExact;
        return $this;
    }

    public function getPathPrefix(): string
    {
        return $this->PathPrefix;
    }

    public function setPathPrefix(string $PathPrefix): self
    {
        $this->PathPrefix = $PathPrefix;
        return $this;
    }

    public function getPathRegex(): string
    {
        return $this->PathRegex;
    }

    public function setPathRegex(string $PathRegex): self
    {
        $this->PathRegex = $PathRegex;
        return $this;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\ConfigEntry\IntentionHTTPHeaderPermission>
     */
    public function getHeader(): array
    {
        return $this->Header;
    }

    public function setHeader(IntentionHTTPHeaderPermission ...$Header): self
    {
        $this->Header = $Header;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getMethods(): array
    {
        return $this->Methods;
    }

    public function setMethods(string ...$Methods): self
    {
        $this->Methods = $Methods;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            if ('path_exact' === $k) {
                $n->PathExact = $v;
            } elseif ('path_prefix' === $k) {
                $n->PathPrefix = $v;
            } elseif ('path_regex' === $k) {
                $n->PathRegex = $v;
            } elseif ('Header' === $k) {
                $n->Header = [];
                foreach ($v as $vv) {
                    $n->Header[] = IntentionHTTPHeaderPermission::jsonUnserialize($vv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        if ('' !== $this->PathExact) {
            $out->PathExact = $this->PathExact;
        }
        if ('' !== $this->PathPrefix) {
            $out->PathPrefix = $this->PathPrefix;
        }
        if ('' !== $this->PathRegex) {
            $out->PathRegex = $this->PathRegex;
        }
        if ([] === $this->Header) {
            $out->Header = $this->Header;
        }
        if ([] !== $this->Methods) {
            $out->Methods = $this->Methods;
        }
        return $out;
    }
}
