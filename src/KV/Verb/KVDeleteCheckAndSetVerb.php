<?php namespace DCarbone\SimpleConsulPHP\KV\Verb;

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
 * Class KVDeleteCheckAndSetVerb
 * @package DCarbone\SimpleConsulPHP\KV\Verb
 */
class KVDeleteCheckAndSetVerb extends AbstractKVPairAwareVerb
{
    /**
     * @return string
     */
    public function getVerb()
    {
        return 'delete-cas';
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return array(
            'Verb' => $this->getVerb(),
            'Key' => $this->_KVPair->getKey(),
            'Index' => $this->_KVPair->getModifyIndex()
        );
    }
}