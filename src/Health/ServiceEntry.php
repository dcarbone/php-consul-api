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
 * Class ServiceEntry
 * @package DCarbone\PHPConsulAPI\Health
 */
class ServiceEntry extends AbstractDefinedCollection
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Node' => null,
            'Service' => null,
            'Checks' => array()
        );
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNode
     */
    public function getNode()
    {
        return $this->_storage['Node'];
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function getService()
    {
        return $this->_storage['Service'];
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealCheck[]
     */
    public function getChecks()
    {
        return $this->_storage['Checks'];
    }
}