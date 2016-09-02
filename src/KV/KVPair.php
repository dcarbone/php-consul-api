<?php namespace DCarbone\PHPConsulAPI\KV;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\AbstractObjectModel;

/**
 * Class KVPair
 * @package DCarbone\PHPConsulAPI\KV
 */
class KVPair extends AbstractObjectModel
{
    /** @var string */
    public $Key = '';
    /** @var int */
    public $CreateIndex = 0;
    /** @var int */
    public $ModifyIndex = 0;
    /** @var int */
    public $LockIndex = 0;
    /** @var int */
    public $Flags = 0;
    /** @var null|string */
    public $Value = null;
    /** @var string */
    public $Session = '';

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->Value;
    }
}