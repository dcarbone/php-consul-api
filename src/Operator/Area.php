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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class Area extends AbstractType
{
    public string $ID;
    public string $PeerDatacenter;
    public array $RetryJoin;
    public bool $UseTLS;

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $id): self
    {
        $this->ID = $id;
        return $this;
    }

    public function getPeerDatacenter(): string
    {
        return $this->PeerDatacenter;
    }

    public function setPeerDatacenter(string $peerDatacenter): self
    {
        $this->PeerDatacenter = $peerDatacenter;
        return $this;
    }

    public function getRetryJoin(): array
    {
        return $this->RetryJoin;
    }

    public function setRetryJoin(array $retryJoin): self
    {
        $this->RetryJoin = $retryJoin;
        return $this;
    }

    public function isUseTLS(): bool
    {
        return $this->UseTLS;
    }

    public function setUseTLS(bool $useTLS): self
    {
        $this->UseTLS = $useTLS;
        return $this;
    }
}
