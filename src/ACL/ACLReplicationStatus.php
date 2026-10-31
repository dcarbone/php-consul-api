<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use Brick\Math\BigInteger;
use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

use function DCarbone\PHPConsulAPI\PHPLib\parse_time;
use function DCarbone\PHPConsulAPI\PHPLib\uint64;

class ACLReplicationStatus extends AbstractType
{
    public bool $Enabled;
    public bool $Running;
    public string $SourceDatacenter;
    public string $ReplicationType;
    public BigInteger $ReplicatedIndex;
    public BigInteger $ReplicatedRoleIndex;
    public BigInteger $ReplicatedTokenIndex;
    public Time\Time $LastSuccess;
    public Time\Time $LastError;
    public string $LastErrorMessage;

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        bool $Enabled = false,
        bool $Running = false,
        string $SourceDatacenter = '',
        string $ReplicationType = '',
        int|string|BigInteger $ReplicatedIndex = 0,
        int|string|BigInteger $ReplicatedRoleIndex = 0,
        int|string|BigInteger $ReplicatedTokenIndex = 0,
        null|Time\Time $LastSuccess = null,
        null|Time\Time $LastError = null,
        string $LastErrorMessage = '',
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Enabled = $Enabled;
        $this->Running = $Running;
        $this->SourceDatacenter = $SourceDatacenter;
        $this->ReplicationType = $ReplicationType;
        $this->ReplicatedIndex = uint64($ReplicatedIndex);
        $this->ReplicatedRoleIndex = uint64($ReplicatedRoleIndex);
        $this->ReplicatedTokenIndex = uint64($ReplicatedTokenIndex);
        $this->LastSuccess = $LastSuccess ?? Time::New();
        $this->LastError = $LastError ?? Time::New();
        $this->LastErrorMessage = $LastErrorMessage;
    }

    public function isEnabled(): bool
    {
        return $this->Enabled;
    }

    public function setEnabled(bool $Enabled): self
    {
        $this->Enabled = $Enabled;
        return $this;
    }

    public function isRunning(): bool
    {
        return $this->Running;
    }

    public function setRunning(bool $Running): self
    {
        $this->Running = $Running;
        return $this;
    }

    public function getSourceDatacenter(): string
    {
        return $this->SourceDatacenter;
    }

    public function setSourceDatacenter(string $SourceDatacenter): self
    {
        $this->SourceDatacenter = $SourceDatacenter;
        return $this;
    }

    public function getReplicationType(): string
    {
        return $this->ReplicationType;
    }

    public function setReplicationType(string $ReplicationType): self
    {
        $this->ReplicationType = $ReplicationType;
        return $this;
    }

    public function getReplicatedIndex(): BigInteger
    {
        return $this->ReplicatedIndex;
    }

    public function setReplicatedIndex(int|string|BigInteger $ReplicatedIndex): self
    {
        $this->ReplicatedIndex = uint64($ReplicatedIndex);
        return $this;
    }

    public function getReplicatedRoleIndex(): BigInteger
    {
        return $this->ReplicatedRoleIndex;
    }

    public function setReplicatedRoleIndex(int|string|BigInteger $ReplicatedRoleIndex): self
    {
        $this->ReplicatedRoleIndex = uint64($ReplicatedRoleIndex);
        return $this;
    }

    public function getReplicatedTokenIndex(): BigInteger
    {
        return $this->ReplicatedTokenIndex;
    }

    public function setReplicatedTokenIndex(int|string|BigInteger $ReplicatedTokenIndex): self
    {
        $this->ReplicatedTokenIndex = uint64($ReplicatedTokenIndex);
        return $this;
    }

    public function getLastSuccess(): Time\Time
    {
        return $this->LastSuccess;
    }

    public function setLastSuccess(Time\Time $LastSuccess): self
    {
        $this->LastSuccess = $LastSuccess;
        return $this;
    }

    public function getLastError(): Time\Time
    {
        return $this->LastError;
    }

    public function setLastError(Time\Time $LastError): self
    {
        $this->LastError = $LastError;
        return $this;
    }

    public function getLastErrorMessage(): string
    {
        return $this->LastErrorMessage;
    }

    public function setLastErrorMessage(string $LastErrorMessage): self
    {
        $this->LastErrorMessage = $LastErrorMessage;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('ReplicatedIndex' === $k || 'ReplicatedRoleIndex' === $k || 'ReplicatedTokenIndex' === $k) {
                $n->{$k} = uint64($v);
            } elseif ('LastSuccess' === $k) {
                $n->LastSuccess = parse_time($v) ?? Time::New();
            } elseif ('LastError' === $k) {
                $n->LastError = parse_time($v) ?? Time::New();
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Enabled = $this->Enabled;
        $out->Running = $this->Running;
        $out->SourceDatacenter = $this->SourceDatacenter;
        $out->ReplicationType = $this->ReplicationType;
        $out->ReplicatedIndex = $this->ReplicatedIndex;
        $out->ReplicatedRoleIndex = $this->ReplicatedRoleIndex;
        $out->ReplicatedTokenIndex = $this->ReplicatedTokenIndex;
        $out->LastSuccess = $this->LastSuccess->RFC3339Nano();
        $out->LastError = $this->LastError->RFC3339Nano();
        $out->LastErrorMessage = $this->LastErrorMessage;
        return $out;
    }
}
