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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class TxnOp
 */
class TxnOp extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_KV      => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => KVTxnOp::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_NODE    => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => NodeTxnOp::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_SERVICE => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => ServiceTxnOp::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_CHECK   => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => CheckTxnOp::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
    ];

    private const FIELD_KV      = 'KV';
    private const FIELD_NODE    = 'Node';
    private const FIELD_SERVICE = 'Service';
    private const FIELD_CHECK   = 'Check';

    /** @var \DCarbone\PHPConsulAPI\KV\KVTxnOp|null */
    public ?KVTxnOp $KV = null;
    /** @var \DCarbone\PHPConsulAPI\KV\NodeTxnOp|null */
    public ?NodeTxnOp $Node = null;
    /** @var \DCarbone\PHPConsulAPI\KV\ServiceTxnOp|null */
    public ?ServiceTxnOp $Service = null;
    /** @var \DCarbone\PHPConsulAPI\KV\CheckTxnOp|null */
    public ?CheckTxnOp $Check = null;

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVTxnOp|null
     */
    public function getKV(): ?KVTxnOp
    {
        return $this->KV;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\KVTxnOp|null $KV
     * @return \DCarbone\PHPConsulAPI\KV\TxnOp
     */
    public function setKV(?KVTxnOp $KV): self
    {
        $this->KV = $KV;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\NodeTxnOp|null
     */
    public function getNode(): ?NodeTxnOp
    {
        return $this->Node;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\NodeTxnOp|null $Node
     * @return \DCarbone\PHPConsulAPI\KV\TxnOp
     */
    public function setNode(?NodeTxnOp $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\ServiceTxnOp|null
     */
    public function getService(): ?ServiceTxnOp
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\ServiceTxnOp|null $Service
     * @return \DCarbone\PHPConsulAPI\KV\TxnOp
     */
    public function setService(?ServiceTxnOp $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\CheckTxnOp|null
     */
    public function getCheck(): ?CheckTxnOp
    {
        return $this->Check;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\KV\CheckTxnOp|null $Check
     * @return \DCarbone\PHPConsulAPI\KV\TxnOp
     */
    public function setCheck(?CheckTxnOp $Check): self
    {
        $this->Check = $Check;
        return $this;
    }
}
