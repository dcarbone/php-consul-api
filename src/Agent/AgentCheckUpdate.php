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

/**
 * Class AgentCheckUpdate
 * @package DCarbone\SimpleConsulPHP\Agent
 */
class AgentCheckUpdate extends AbstractDefinedCollection
{
    /** @var array */
    protected $_storage = array(
        'Status' => null,
        'Output' => null
    );

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->_storage['Status'];
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        switch($status)
        {
            case 'pass':
            case 'passing':
                $status = 'passing';
                break;
            case 'warn':
            case 'warning':
                $status = 'warning';
                break;
            case 'fail':
            case 'critical':
                $status = 'critical';
                break;

            default:
                throw new \InvalidArgumentException(sprintf(
                    '%s - Agent Check statuses must be one of the following: passing, warning, critical.  %s seen.',
                    get_class($this),
                    is_string($status) ? $status : gettype($status)
                ));
        }

        $this->_storage['Status'] = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->_storage['Output'];
    }

    /**
     * @param string $output
     * @return $this
     */
    public function setOutput($output)
    {
        $this->_storage['Output'] = $output;
        return $this;
    }
}