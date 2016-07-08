<?php namespace DCarbone\PHPConsulAPI\Catalog;

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
 * Class CatalogNode
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogNode extends AbstractDefinedCollection
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Node' => null,
            'Address' => null
        );
    }

    /**
     * @return string
     */
    public function getNode()
    {
        return (string)$this['Node'];
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return (string)$this['Address'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this['Node'];
    }
}