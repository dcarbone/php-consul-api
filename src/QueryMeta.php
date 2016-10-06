<?php namespace DCarbone\PHPConsulAPI;

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

/**
 * Class QueryMeta
 * @package DCarbone\PHPConsulAPI
 */
class QueryMeta
{
    /** @var string */
    public $requestUrl = '';
    /** @var int */
    public $lastIndex = 0;
    /** @var int */
    public $lastContact = 0;
    /** @var bool */
    public $knownLeader = false;
    /** @var int */
    public $requestTime = 0;

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @return int
     */
    public function getLastIndex()
    {
        return $this->lastIndex;
    }

    /**
     * @return int
     */
    public function getLastContact()
    {
        return $this->lastContact;
    }

    /**
     * @return boolean
     */
    public function isKnownLeader()
    {
        return $this->knownLeader;
    }

    /**
     * @return int
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }
}