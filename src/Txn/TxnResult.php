<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Txn;

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

use DCarbone\PHPConsulAPI\Catalog\CatalogService;
use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class TxnResult extends AbstractType
{
    public null|KVPair $KV;
    public null|Node $Node;
    public null|CatalogService $Service;
    public null|HealthCheck $Check;

    public function __construct(
        null|KVPair $KV = null,
        null|Node $Node = null,
        null|CatalogService $Service = null,
        null|HealthCheck $Check = null,
    ) {
        $this->KV = $KV;
        $this->Node = $Node;
        $this->Service = $Service;
        $this->Check = $Check;
    }

    public function getKV(): null|KVPair
    {
        return $this->KV;
    }

    public function setKV(null|KVPair $KV): self
    {
        $this->KV = $KV;
        return $this;
    }

    public function getNode(): null|Node
    {
        return $this->Node;
    }

    public function setNode(null|Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getService(): null|CatalogService
    {
        return $this->Service;
    }

    public function setService(null|CatalogService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getCheck(): null|HealthCheck
    {
        return $this->Check;
    }

    public function setCheck(null|HealthCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('KV' === $k) {
                $n->KV = null === $v ? null : KVPair::jsonUnserialize($v);
            } elseif ('Node' === $k) {
                $n->Node = null === $v ? null : Node::jsonUnserialize($v);
            } elseif ('Service' === $k) {
                $n->Service = null === $v ? null : CatalogService::jsonUnserialize($v);
            } elseif ('Check' === $k) {
                $n->Check = null === $v ? null : HealthCheck::jsonUnserialize($v);
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
