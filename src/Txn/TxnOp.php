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

class TxnOp extends AbstractType
{
    public null|KVTxnOp $KV;
    public null|NodeTxnOp $Node;
    public null|ServiceTxnOp $Service;
    public null|CheckTxnOp $Check;

    public function __construct(
        null|KVTxnOp $KV = null,
        null|NodeTxnOp $Node = null,
        null|ServiceTxnOp $Service = null,
        null|CheckTxnOp $Check = null,
    ) {
        $this->KV = $KV;
        $this->Node = $Node;
        $this->Service = $Service;
        $this->Check = $Check;
    }

    public function getKV(): null|KVTxnOp
    {
        return $this->KV;
    }

    public function setKV(null|KVTxnOp $KV): self
    {
        $this->KV = $KV;
        return $this;
    }

    public function getNode(): null|NodeTxnOp
    {
        return $this->Node;
    }

    public function setNode(null|NodeTxnOp $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getService(): null|ServiceTxnOp
    {
        return $this->Service;
    }

    public function setService(null|ServiceTxnOp $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getCheck(): null|CheckTxnOp
    {
        return $this->Check;
    }

    public function setCheck(null|CheckTxnOp $Check): self
    {
        $this->Check = $Check;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('KV' === $k) {
                $n->KV = null === $v ? null : KVTxnOp::jsonUnserialize($v);
            } elseif ('Node' === $k) {
                $n->Node = null === $v ? null : NodeTxnOp::jsonUnserialize($v);
            } elseif ('Service' === $k) {
                $n->Service = null === $v ? null : ServiceTxnOp::jsonUnserialize($v);
            } elseif ('Check' === $k) {
                $n->Check = null === $v ? null : CheckTxnOp::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (null !== $this->KV) {
            $out->KV = $this->KV;
        }
        if (null !== $this->Node) {
            $out->Node = $this->Node;
        }
        if (null !== $this->Service) {
            $out->Service = $this->Service;
        }
        if (null !== $this->Check) {
            $out->Check = $this->Check;
        }
        return $out;
    }
}
