<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Catalog\CatalogService;
use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Health\HealthCheck;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class TxnResult
 */
class TxnResult extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_KV      => [
            Hydration::FIELD_TYPE     => Hydration::OBJECT,
            Hydration::FIELD_CLASS    => KVPair::class,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_NODE    => [
            Hydration::FIELD_TYPE     => Hydration::OBJECT,
            Hydration::FIELD_CLASS    => Node::class,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_SERVICE => [
            Hydration::FIELD_TYPE     => Hydration::OBJECT,
            Hydration::FIELD_CLASS    => CatalogService::class,
            Hydration::FIELD_NULLABLE => true,
        ],
        self::FIELD_CHECK   => [
            Hydration::FIELD_TYPE     => Hydration::OBJECT,
            Hydration::FIELD_CLASS    => HealthCheck::class,
            Hydration::FIELD_NULLABLE => true,
        ],
    ];

    private const FIELD_KV      = 'KV';
    private const FIELD_NODE    = 'Node';
    private const FIELD_SERVICE = 'Service';
    private const FIELD_CHECK   = 'Check';

    /** @var \DCarbone\PHPConsulAPI\KV\KVPair|null */
    public ?KVPair $KV = null;
    /** @var \DCarbone\PHPConsulAPI\Catalog\Node|null */
    public ?Node $Node = null;
    /** @var \DCarbone\PHPConsulAPI\Catalog\CatalogService|null */
    public ?CatalogService $Service = null;
    /** @var \DCarbone\PHPConsulAPI\Health\HealthCheck|null */
    public ?HealthCheck $Check = null;

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVPair|null
     */
    public function getKV(): ?KVPair
    {
        return $this->KV;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVPair|null $KV
     * @return \DCarbone\PHPConsulAPI\KV\TxnResult
     */
    public function setKV(?KVPair $KV): self
    {
        $this->KV = $KV;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\Node|null
     */
    public function getNode(): ?Node
    {
        return $this->Node;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\Node|null $Node
     * @return \DCarbone\PHPConsulAPI\KV\TxnResult
     */
    public function setNode(?Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogService|null
     */
    public function getService(): ?CatalogService
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\CatalogService|null $Service
     * @return \DCarbone\PHPConsulAPI\KV\TxnResult
     */
    public function setService(?CatalogService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthCheck|null
     */
    public function getCheck(): ?HealthCheck
    {
        return $this->Check;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Health\HealthCheck|null $Check
     * @return \DCarbone\PHPConsulAPI\KV\TxnResult
     */
    public function setCheck(?HealthCheck $Check): self
    {
        $this->Check = $Check;
        return $this;
    }
}
