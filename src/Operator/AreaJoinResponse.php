<?php namespace DCarbone\PHPConsulAPI\Operator;

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
 * Class AreaJoinResponse
 * @package DCarbone\PHPConsulAPI\Operator
 */
class AreaJoinResponse extends AbstractModel {
    /** @var string */
    public $Address = '';
    /** @var bool */
    public $Joined = false;
    /** @var string */
    public $Error = '';

    /**
     * @return string
     */
    public function getAddress(): string {
        return $this->Address;
    }

    /**
     * @return bool
     */
    public function isJoined(): bool {
        return $this->Joined;
    }

    /**
     * @return string
     */
    public function getError(): string {
        return $this->Error;
    }
}