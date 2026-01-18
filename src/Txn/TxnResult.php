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

use DCarbone\PHPConsulAPI\KV\KVPair;
use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;
use DCarbone\PHPConsulAPI\Catalog\CatalogService;
use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Transcoding;

class TxnResult extends AbstractType
{
    protected const FIELDS = [
        self::FIELD_KV      => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => KVPair::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_NODE    => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => Node::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_SERVICE => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => CatalogService::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_CHECK   => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => HealthCheck::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
    ];

    private const FIELD_KV      = 'KV';
    private const FIELD_NODE    = 'Node';
    private const FIELD_SERVICE = 'Service';
    private const FIELD_CHECK   = 'Check';

    public ?KVPair $KV = null;
    public ?Node $Node = null;
    public ?CatalogService $Service = null;
    public ?HealthCheck $Check = null;

    public function getKV(): ?KVPair
    {
        return $this->KV;
    }

    public function setKV(?KVPair $KV): self
    {
        $this->KV = $KV;
        return $this;
    }

    public function getNode(): ?Node
    {
        return $this->Node;
    }

    public function setNode(?Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getService(): ?CatalogService
    {
        return $this->Service;
    }

    public function setService(?CatalogService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getCheck(): ?HealthCheck
    {
        return $this->Check;
    }

    public function setCheck(?HealthCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }
}
