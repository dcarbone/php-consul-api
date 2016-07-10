<?php namespace DCarbone\PHPConsulAPI\Health;

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
 * Class HealthCheck
 * @package DCarbone\PHPConsulAPI\Health
 */
class HealthCheck extends AbstractDefinedCollection
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Node' => null,
            'CheckID' => null,
            'Name' => null,
            'Status' => null,
            'Notes' => null,
            'Output' => null,
            'ServiceID' => null,
            'ServiceName' => null
        );
    }

    /**
     * @return string
     */
    public function getNode()
    {
        return (string)$this->_storage['Node'];
    }

    /**
     * @return string
     */
    public function getCheckID()
    {
        return (string)$this->_storage['CheckID'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->_storage['Name'];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return (string)$this->_storage['Status'];
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return (string)$this->_storage['Output'];
    }

    /**
     * @return string
     */
    public function getServiceID()
    {
        return (string)$this->_storage['ServiceID'];
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return (string)$this->_storage['ServiceName'];
    }
}