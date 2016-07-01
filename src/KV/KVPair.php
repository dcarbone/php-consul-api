<?php namespace DCarbone\SimpleConsulPHP\KV;

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

use DCarbone\SimpleConsulPHP\Base\AbstractResponseModel;

/**
 * Class KVPair
 * @package DCarbone\SimpleConsulPHP\KV
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

    /** @var KVClient */
    private $_KVClient;

    /**
     * AbstractResponseModel constructor.
     * @param array $data (
     *      'Key' => string,
     *      'Value' => string,
     *      'Flags' => integer
     * )
     * @param KVClient $KVClient
     */
    public function __construct(array $data, KVClient $KVClient)
    {
        parent::__construct($data);
        $this->_KVClient = $KVClient;
    }

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
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        if (512 >= mb_strlen($value, '8bit'))
        {
            $this['Value'] = base64_encode((string)$value);
            return $this;
        }

        throw new \InvalidArgumentException(sprintf(
            '%s::setValue - Provided value is longer than the 512kB limit.  Value seen: "%s"',
            get_class($this),
            $value
        ));
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
     * @param int $flags
     * @return $this
     */
    public function setFlags($flags)
    {
        $this['Flags'] = (int)$flags;
        return $this;
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return $this['Session'];
    }

    /**
     * @param string $session
     * @return $this
     */
    public function setSession($session)
    {
        $this['Session'] = $session;
        return $this;
    }

    /**
     * @return KVClient
     */
    public function getKVClient()
    {
        return $this->_KVClient;
    }
}