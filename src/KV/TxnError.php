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
 * Class TxnError
 * @package DCarbone\PHPConsulAPI\KV
 */
class TxnError extends AbstractModel {
    /** @var int */
    public $OpIndex = 0;
    /** @var string */
    public $What = '';

    /**
     * @return int
     */
    public function getOpIndex(): int {
        return $this->OpIndex;
    }

    /**
     * @return string
     */
    public function getWhat(): string {
        return $this->What;
    }
}