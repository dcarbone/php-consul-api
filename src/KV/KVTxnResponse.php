<?php namespace DCarbone\PHPConsulAPI\KV;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class KVTxnResponse
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVTxnResponse extends AbstractModel {
    /** @var \DCarbone\PHPConsulAPI\KV\KVTxnOp[] */
    public $Results = [];
    /** @var \DCarbone\PHPConsulAPI\KV\TxnError[] */
    public $Errors = [];

    /**
     * KVTxnResponse constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);
        if (0 < count($this->Results)) {
            $this->Results = array_filter($this->Results);
            if (0 < ($cnt = count($this->Results))) {
                for ($i = 0; $i < $cnt; $i++) {
                    if (!($this->Results[$i] instanceof KVTxnOp)) {
                        $this->Results[$i] = new KVTxnOp($this->Results[$i]);
                    }
                }
            }
        }
        if (0 < count($this->Errors)) {
            $this->Errors = array_filter($this->Errors);
            if (0 < ($cnt = count($this->Errors))) {
                for ($i = 0; $i < $cnt; $i++) {
                    if (!($this->Errors[$i]) instanceof TxnError) {
                        $this->Errors[$i] = new TxnError($this->Errors[$i]);
                    }
                }
            }
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVTxnOp[]
     */
    public function getResults(): array {
        return $this->Results;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\TxnError[]
     */
    public function getErrors(): array {
        return $this->Errors;
    }
}