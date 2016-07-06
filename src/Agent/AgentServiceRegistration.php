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


use DCarbone\SimpleConsulPHP\AbstractDefinedCollection;
use DCarbone\SimpleConsulPHP\TaggableInterface;

/**
 * Class AgentServiceRegistration
 * @package DCarbone\SimpleConsulPHP\Agent
 */
class AgentServiceRegistration extends AbstractDefinedCollection implements TaggableInterface
{
    /** @var array */
    protected $_storage = array(
        'ID' => null,
        'Name' => null,
        'Tags' => array(),
        'Port' => null,
        'Address' => null,
        'EnableTagOverride' => false,
        'Check' => null,
        'Checks' => array(),
    );

    /**
     * @return string
     */
    public function getID()
    {
        return $this->_storage['ID'];
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setID($id)
    {
        $this->_storage['ID'] = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_storage['Name'];
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->_storage['Name'] = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->_storage['Tags'];
    }

    /**
     * @param string $tag
     * @return bool
     */
    public function hasTag($tag)
    {
        return isset($this->_storage['Tags'][$tag]) || array_key_exists($tag, $this->_storage['Tags']);
    }

    /**
     * @param string $tag
     * @return string|null
     */
    public function getTag($tag)
    {
        if ($this->hasTag($tag))
            return $this->_storage['Tags'][$tag];

        return null;
    }

    /**
     * @param string $tag
     * @param string $value
     * @return $this
     */
    public function addTag($tag, $value)
    {
        $this->_storage['Tags'][$tag] = $value;
        return $this;
    }

    /**
     * @param array $tags
     * @return $this
     */
    public function setTags(array $tags = array())
    {
        $this->_storage['Tags'] = $tags;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return (int)$this->_storage['Port'];
    }

    /**
     * @param int $port
     * @return $this
     */
    public function setPort($port)
    {
        $this->_storage['Port'] = (int)$port;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->_storage['Address'];
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->_storage['Address'] = $address;
        return $this;
    }

    /**
     * @return bool
     */
    public function getEnableTagOverride()
    {
        return (bool)$this->_storage['EnableTagOverride'];
    }

    /**
     * @param bool $enableTagOverride
     * @return $this
     */
    public function setEnableTagOverride($enableTagOverride)
    {
        $this->_storage['EnableTagOverride'] = (bool)$enableTagOverride;
        return $this;
    }

    /**
     * @return AgentServiceCheck|null
     */
    public function getCheck()
    {
        return $this->_storage['Check'];
    }

    /**
     * @param AgentServiceCheck $agentServiceCheck
     * @return $this
     */
    public function setCheck(AgentServiceCheck $agentServiceCheck)
    {
        $this->_storage['Check'] = $agentServiceCheck;
        return $this;
    }

    /**
     * @return AgentServiceCheck[]
     */
    public function getChecks()
    {
        return $this->_storage['Checks'];
    }

    /**
     * @param AgentServiceCheck[] $agentServiceChecks
     * @return $this
     */
    public function setChecks(array $agentServiceChecks)
    {
        $this->_storage['Checks'] = array();
        foreach($agentServiceChecks as $check)
        {
            if ($check instanceof AgentServiceCheck)
            {
                $this->_storage['Checks'][] = $check;
                continue;
            }

            throw new \InvalidArgumentException(sprintf(
                '%s::setChecks - Expected array of AgentServiceCheck objects, saw "%s".',
                get_class($this),
                is_object($check) ? get_class($check) : gettype($check)
            ));
        }
        return $this;
    }
}