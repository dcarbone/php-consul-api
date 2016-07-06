<?php namespace DCarbone\SimpleConsulPHP\Agent;

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

use DCarbone\SimpleConsulPHP\AbstractResponseModel;
use DCarbone\SimpleConsulPHP\TaggableInterface;

/**
 * Class AgentMember
 * @package DCarbone\SimpleConsulPHP\Agent
 */
class AgentMember extends AbstractResponseModel implements TaggableInterface
{
    /** @var array */
    protected static $default = array(
        'Name' => null,
        'Addr' => null,
        'Port' => null,
        'Tags' => array(),
        'Status' => null,
        'ProtocolMin' => null,
        'ProtocolMax' => null,
        'ProtocolCur' => null,
        'DelegateMin' => null,
        'DelegateMax' => null,
        'DelegateCur' => null,
    );

    /**
     * @return string
     */
    public function getName()
    {
        return $this['Name'];
    }

    /**
     * @return string
     */
    public function getAddr()
    {
        return $this['Addr'];
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return (int)$this['Port'];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this['Tags'];
    }

    /**
     * @param string $tag
     * @return bool
     */
    public function hasTag($tag)
    {
        return isset($this['Tags'][$tag]) || array_key_exists($tag, $this['Tags']);
    }

    /**
     * @param string $tag
     * @return string|null
     */
    public function getTag($tag)
    {
        if ($this->hasTag($tag))
            return $this['Tags'][$tag];

        return null;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return (int)$this['Status'];
    }

    /**
     * @return int
     */
    public function getProtocolMin()
    {
        return (int)$this['ProtocolMin'];
    }

    /**
     * @return int
     */
    public function getProtocolMax()
    {
        return (int)$this['ProtocolMax'];
    }

    /**
     * @return int
     */
    public function getProtocolCur()
    {
        return (int)$this['ProtocolCur'];
    }

    /**
     * @return int
     */
    public function getDelegateMin()
    {
        return (int)$this['DelegateMin'];
    }

    /**
     * @return int
     */
    public function getDelegateMax()
    {
        return (int)$this['DelegateMax'];
    }

    /**
     * @return int
     */
    public function getDelegateCur()
    {
        return (int)$this['DelegateCur'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}