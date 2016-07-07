<?php namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\AbstractDefinedCollection;
use DCarbone\PHPConsulAPI\TaggedInterface;

/**
 * Class AgentService
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentService extends AbstractDefinedCollection implements TaggedInterface
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'ID' => null,
            'Service' => null,
            'Tags' => array(),
            'Address' => null,
            'Port' => null,
            'EnableTagOverride' => null
        );
    }

    /**
     * @return string
     */
    public function getID()
    {
        return $this['ID'];
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this['Service'];
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
     * @return string
     */
    public function getAddress()
    {
        return $this['Address'];
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return (int)$this['Port'];
    }

    /**
     * @return bool
     */
    public function getEnableTagOverride()
    {
        return (bool)$this['EnableTagOverride'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getID();
    }
}