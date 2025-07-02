<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;

class ACLReplicationStatus extends AbstractModel
{
    public bool $Enabled;
    public bool $Running;
    public string $SourceDatacenter;
    public int $ReplicatedIndex;
    public int $ReplicatedRoleIndex;
    public int $ReplicatedTokenIndex;
    public Time\Time $LastSuccess;
    public Time\Time $LastError;

    public function __construct(
        bool $Enabled = false,
        bool $Running = false,
        string $SourceDatacenter = '',
        int $ReplicatedIndex = 0,
        int $ReplicatedRoleIndex = 0,
        int $ReplicatedTokenIndex = 0,
        null|Time\Time $LastSuccess = null,
        null|Time\Time $LastError = null,
    ) {
        $this->Enabled = $Enabled;
        $this->Running = $Running;
        $this->SourceDatacenter = $SourceDatacenter;
        $this->ReplicatedIndex = $ReplicatedIndex;
        $this->ReplicatedRoleIndex = $ReplicatedRoleIndex;
        $this->ReplicatedTokenIndex = $ReplicatedTokenIndex;
        $this->LastSuccess = $LastSuccess ?? Time::New();
        $this->LastError = $LastError ?? Time::New();
}

    public function isEnabled(): bool
    {
        return $this->Enabled;
    }

    public function isRunning(): bool
    {
        return $this->Running;
    }

    public function getSourceDatacenter(): string
    {
        return $this->SourceDatacenter;
    }

    public function getReplicatedIndex(): int
    {
        return $this->ReplicatedIndex;
    }

    public function getReplicatedRoleIndex(): int
    {
        return $this->ReplicatedRoleIndex;
    }

    public function getReplicatedTokenIndex(): int
    {
        return $this->ReplicatedTokenIndex;
    }

    public function getLastSuccess(): Time\Time
    {
        return $this->LastSuccess;
    }

    public function getLastError(): Time\Time
    {
        return $this->LastError;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('LastSuccess' === $k) {
                $n->LastSuccess = Time\Time::createFromFormat(DATE_RFC3339, $v);
            } elseif ('LastError' === $k) {
                $n->LastError = Time\Time::createFromFormat(DATE_RFC3339, $v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Enabled = $this->Enabled;
        $out->Running = $this->Running;
        $out->SourceDatacenter = $this->SourceDatacenter;
        $out->ReplicatedIndex = $this->ReplicatedIndex;
        $out->ReplicatedRoleIndex = $this->ReplicatedRoleIndex;
        $out->ReplicatedTokenIndex = $this->ReplicatedTokenIndex;
        $out->LastSuccess = $this->LastSuccess->format(DATE_RFC3339);
        $out->LastError = $this->LastError->format(DATE_RFC3339);
        return $out;
    }
}
