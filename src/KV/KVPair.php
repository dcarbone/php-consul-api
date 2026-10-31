<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

use function DCarbone\PHPConsulAPI\PHPLib\uint64;

class KVPair extends AbstractType
{
    public string $Key;
    public BigInteger $CreateIndex;
    public BigInteger $ModifyIndex;
    public BigInteger $LockIndex;
    public BigInteger $Flags;
    public string $Value;
    public string $Session;
    public string $Namespace;
    public string $Partition;

    /**
     * @param null|array<string,mixed> $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $Key = '',
        int|string|BigInteger $CreateIndex = 0,
        int|string|BigInteger $ModifyIndex = 0,
        int|string|BigInteger $LockIndex = 0,
        int|string|BigInteger $Flags = 0,
        string $Value = '',
        string $Session = '',
        string $Namespace = '',
        string $Partition = '',
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->Key = $Key;
        $this->CreateIndex = uint64($CreateIndex);
        $this->ModifyIndex = uint64($ModifyIndex);
        $this->LockIndex = uint64($LockIndex);
        $this->Flags = uint64($Flags);
        $this->Value = $Value;
        $this->Session = $Session;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
    }

    public function getKey(): string
    {
        return $this->Key;
    }

    public function setKey(string $Key): self
    {
        $this->Key = $Key;
        return $this;
    }

    public function getCreateIndex(): BigInteger
    {
        return $this->CreateIndex;
    }

    public function setCreateIndex(int|string|BigInteger $CreateIndex): self
    {
        $this->CreateIndex = uint64($CreateIndex);
        return $this;
    }

    public function getModifyIndex(): BigInteger
    {
        return $this->ModifyIndex;
    }

    public function setModifyIndex(int|string|BigInteger $ModifyIndex): self
    {
        $this->ModifyIndex = uint64($ModifyIndex);
        return $this;
    }

    public function getLockIndex(): BigInteger
    {
        return $this->LockIndex;
    }

    public function setLockIndex(int|string|BigInteger $LockIndex): self
    {
        $this->LockIndex = uint64($LockIndex);
        return $this;
    }

    public function getFlags(): BigInteger
    {
        return $this->Flags;
    }

    public function setFlags(int|string|BigInteger $Flags): self
    {
        $this->Flags = uint64($Flags);
        return $this;
    }

    public function getValue(): string
    {
        return $this->Value;
    }

    public function setValue(string $Value): self
    {
        $this->Value = $Value;
        return $this;
    }

    public function getSession(): string
    {
        return $this->Session;
    }

    public function setSession(string $Session): self
    {
        $this->Session = $Session;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public function __toString(): string
    {
        return $this->Value;
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
            if ('Value' === $k) {
                if (null === $v) {
                    $n->Value = '';
                    continue;
                }
                $val = base64_decode($v, true);
                if (false === $val) {
                    throw new \DomainException(sprintf('Could not base64 decode value "%s"', $v));
                }
                $n->Value = $val;
            } elseif ('CreateIndex' === $k || 'ModifyIndex' === $k || 'LockIndex' === $k || 'Flags' === $k) {
                $n->{$k} = uint64($v);
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Key = $this->Key;
        $out->CreateIndex = $this->CreateIndex;
        $out->ModifyIndex = $this->ModifyIndex;
        $out->LockIndex = $this->LockIndex;
        $out->Flags = $this->Flags;
        $out->Value = $this->Value;
        $out->Session = $this->Session;
        if ('' !== $this->Namespace) {
            $out->Namespace = $this->Namespace;
        }
        if ('' !== $this->Partition) {
            $out->Partition = $this->Partition;
        }
        return $out;
    }
}
