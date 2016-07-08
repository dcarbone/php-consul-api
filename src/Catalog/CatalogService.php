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

/**
 * Class CatalogService
 * @package DCarbone\PHPConsulAPI\Catalog
 */
class CatalogService extends CatalogNode
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return parent::getDefinition() + array(
            'ServiceID' => null,
            'ServiceName' => null,
            'ServiceAddress' => null,
            'ServiceTags' => array(),
            'ServicePort' => null,
            'ServiceEnableTagOverride' => null,
        );
    }

    /**
     * @return string
     */
    public function getServiceID()
    {
        return (string)$this['ServiceID'];
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return (string)$this['ServiceName'];
    }

    /**
     * @return string
     */
    public function getServiceAddress()
    {
        return (string)$this['ServiceAddress'];
    }

    /**
     * @return array
     */
    public function getServiceTags()
    {
        return $this['ServiceTags'];
    }

    /**
     * @return int
     */
    public function getServicePort()
    {
        return (int)$this['ServicePort'];
    }

    /**
     * @return bool
     */
    public function getServiceEnableTagOverride()
    {
        return (bool)$this['ServiceEnableTagOverride'];
    }
}