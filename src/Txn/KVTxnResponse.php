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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class KVTxnResponse extends AbstractType
{
    /** @var array<\DCarbone\PHPConsulAPI\Txn\TxnResult> */
    public array $Results;
    /** @var array<\DCarbone\PHPConsulAPI\Txn\TxnError> */
    public array $Errors;

    public function __construct()
    {
        $this->Results = [];
        $this->Errors = [];
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\Txn\TxnResult>
     */
    public function getResults(): array
    {
        return $this->Results;
    }

    /**
     * @return array<\DCarbone\PHPConsulAPI\Txn\TxnError>
     */
    public function getErrors(): array
    {
        return $this->Errors;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Results' === $k) {
                $n->Results = [];
                foreach ($v as $vv) {
                    $n->Results[] = TxnResult::jsonUnserialize($vv);
                }
            } elseif ('Errors' === $k) {
                $n->Errors = [];
                foreach ($v as $vv) {
                    $n->Errors[] = TxnError::jsonUnserialize($vv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Results = $this->Results;
        $out->Errors = $this->Errors;
        return $out;
    }
}
