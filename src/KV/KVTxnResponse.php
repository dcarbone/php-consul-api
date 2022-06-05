<?php

declare(strict_types=1);

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
 * Class KVTxnResponse
 */
class KVTxnResponse extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_RESULTS => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => KVPair::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
        self::FIELD_ERRORS  => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => TxnErrors::class,
        ],
    ];

    private const FIELD_RESULTS = 'Results';
    private const FIELD_ERRORS  = 'Errors';

    /** @var \DCarbone\PHPConsulAPI\KV\KVPair[] */
    public array $Results = [];
    /** @var \DCarbone\PHPConsulAPI\KV\TxnErrors|null */
    public ?TxnErrors $Errors = null;

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVPair[]
     */
    public function getResults(): array
    {
        return $this->Results;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\TxnErrors|null
     */
    public function getErrors(): ?TxnErrors
    {
        return $this->Errors;
    }
}
