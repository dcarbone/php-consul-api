<?php namespace DCarbone\PHPConsulAPI\Event;


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

/**
 * Class UserEvent
 * @package DCarbone\PHPConsulAPI\Event
 */
class UserEvent extends AbstractDefinedCollection
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'ID' => null,
            'Name' => null,
            'Payload' => null,
            'NodeFilter' => null,
            'ServiceFilter' => null,
            'TagFilter' => null,
            'Version' => null,
            'LTime' => null
        );
    }

    /**
     * @return string
     */
    public function getID()
    {
        return (string)$this->_storage['ID'];
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
        return (string)$this->_storage['Name'];
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
     * @return string
     */
    public function getPayload()
    {
        return (string)$this->_storage['Payload'];
    }

    /**
     * @param string $payload
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->_storage['Payload'] = $payload;

        return $this;
    }

    /**
     * @return string
     */
    public function getNodeFilter()
    {
        return (string)$this->_storage['NodeFilter'];
    }

    /**
     * @param string $nodeFilter
     * @return $this
     */
    public function setNodeFilter($nodeFilter)
    {
        $this->_storage['NodeFilter'] = $nodeFilter;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceFilter()
    {
        return (string)$this->_storage['ServiceFilter'];
    }

    /**
     * @param string $serviceFilter
     * @return $this
     */
    public function setServiceFilter($serviceFilter)
    {
        $this->_storage['ServiceFilter'] = $serviceFilter;

        return $this;
    }

    /**
     * @return string
     */
    public function getTagFilter()
    {
        return (string)$this->_storage['TagFilter'];
    }

    /**
     * @param string $tagFilter
     * @return $this
     */
    public function setTagFilter($tagFilter)
    {
        $this->_storage['TagFilter'] = $tagFilter;

        return $this;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return (int)$this->_storage['Version'];
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->_storage['Version'] = $version;

        return $this;
    }

    /**
     * @return int
     */
    public function getLTime()
    {
        return (int)$this->_storage['LTime'];
    }

    /**
     * @param int $lTime
     * @return $this
     */
    public function setLTime($lTime)
    {
        $this->_storage['LTime'] = $lTime;

        return $this;
    }
}