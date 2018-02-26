<?php namespace DCarbone\PHPConsulAPI\KV;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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
    /** @var \DCarbone\PHPConsulAPI\KV\KVPair[] */
    public $Results = [];
    /** @var \DCarbone\PHPConsulAPI\KV\TxnErrors */
    public $Errors = null;

    /**
     * KVTxnResponse constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);
        if (is_array($this->Results)) {
            $this->Results = array_filter((array)$this->Results);
            foreach ($this->Results as &$result) {
                if (!($result instanceof KVPair)) {
                    $result = new KVPair($result, true);
                }
            }
        }
        if (!($this->Errors instanceof TxnErrors)) {
            $this->Errors = new TxnErrors($this->Errors);
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVPair[]
     */
    public function getResults(): array {
        return $this->Results;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\TxnErrors
     */
    public function getErrors(): TxnErrors {
        return $this->Errors;
    }
}