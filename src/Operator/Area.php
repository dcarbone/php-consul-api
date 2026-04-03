<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class Area extends AbstractType
{
    public string $ID;
    public string $PeerDatacenter;
    /** @var array<string> */
    public array $RetryJoin;
    public bool $UseTLS;

    /**
     * @param array<string> $RetryJoin
     */
    public function __construct(
        string $ID = '',
        string $PeerDatacenter = '',
        array $RetryJoin = [],
        bool $UseTLS = false,
    ) {
        $this->ID = $ID;
        $this->PeerDatacenter = $PeerDatacenter;
        $this->setRetryJoin(...$RetryJoin);
        $this->UseTLS = $UseTLS;
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    public function getPeerDatacenter(): string
    {
        return $this->PeerDatacenter;
    }

    public function setPeerDatacenter(string $PeerDatacenter): self
    {
        $this->PeerDatacenter = $PeerDatacenter;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getRetryJoin(): array
    {
        return $this->RetryJoin;
    }

    public function setRetryJoin(string ...$RetryJoin): self
    {
        $this->RetryJoin = $RetryJoin;
        return $this;
    }

    public function isUseTLS(): bool
    {
        return $this->UseTLS;
    }

    public function setUseTLS(bool $UseTLS): self
    {
        $this->UseTLS = $UseTLS;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ID = $this->ID;
        $out->PeerDatacenter = $this->PeerDatacenter;
        $out->RetryJoin = $this->RetryJoin;
        $out->UseTLS = $this->UseTLS;
        return $out;
    }
}
