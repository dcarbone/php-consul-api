<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Txn;

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

class KVTxnOp extends AbstractType
{
    public KVOp $Verb;
    public string $Key;
    public string $Value;
    public int $Flags;
    public int $Index;
    public string $Session;
    public string $Namespace;
    public string $Partition;

    public function __construct(
        string|KVOp $Verb = KVOp::UNDEFINED,
        string $Key = '',
        string $Value = '',
        int $Flags = 0,
        int $Index = 0,
        string $Session = '',
        string $Namespace = '',
        string $Partition = '',
    ) {
        if (is_string($Verb)) {
            $Verb = KVOp::from($Verb);
        }
        $this->Verb = $Verb;
        $this->Key = $Key;
        $this->Value = $Value;
        $this->Flags = $Flags;
        $this->Index = $Index;
        $this->Session = $Session;
        $this->Namespace = $Namespace;
        $this->Partition = $Partition;
    }

    public function getVerb(): KVOp
    {
        return $this->Verb;
    }

    public function setVerb(string|KVOp $Verb): self
    {
        if (is_string($Verb)) {
            $Verb = KVOp::from($Verb);
        }
        $this->Verb = $Verb;
        return $this;
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

    public function getValue(): string
    {
        return $this->Value;
    }

    public function setValue(string $Value): self
    {
        $this->Value = $Value;
        return $this;
    }

    public function getFlags(): int
    {
        return $this->Flags;
    }

    public function setFlags(int $Flags): self
    {
        $this->Flags = $Flags;
        return $this;
    }

    public function getIndex(): int
    {
        return $this->Index;
    }

    public function setIndex(int $Index): self
    {
        $this->Index = $Index;
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Verb' === $k) {
                $n->Verb = KVOp::from($v);
            } elseif ('Value' === $k) {
                if (null === $v) {
                    $n->Value = '';
                    continue;
                }
                $val = base64_decode($v, true);
                if (false === $val) {
                    throw new \DomainException(sprintf('Could not base64 decode value "%s"', $v));
                }
                $n->Value = $val;
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Verb = $this->Verb;
        $out->Key = $this->Key;
        $out->Value = base64_encode($this->Value);
        $out->Flags = $this->Flags;
        $out->Index = $this->Index;
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
