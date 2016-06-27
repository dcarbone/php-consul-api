<?php namespace DCarbone\SimpleConsulPHP\Response\Model;

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
 * Class KVPair
 * @package DCarbone\SimpleConsulPHP\Model
 */
class KVPair extends AbstractResponseModel
{
    /** @var array */
    protected static $default = array(
        'CreateIndex' => null,
        'ModifyIndex' => null,
        'LockIndex' => null,
        'Key' => null,
        'Flags' => null,
        'Value' => null,
        'Session' => null
    );

    /**
     * @return int
     */
    public function getCreateIndex()
    {
        return $this['CreateIndex'];
    }

    /**
     * @return int
     */
    public function getModifyIndex()
    {
        return $this['ModifyIndex'];
    }

    /**
     * @return int
     */
    public function getLockIndex()
    {
        return $this['LockIndex'];
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this['Key'];
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return base64_decode($this['Value']);
    }

    /**
     * @return string
     */
    public function getEncodedValue()
    {
        return $this['Value'];
    }

    /**
     * @return int
     */
    public function getFlags()
    {
        return $this['Flags'];
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return $this['Session'];
    }
}