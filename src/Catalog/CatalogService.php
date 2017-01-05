<?php namespace DCarbone\PHPConsulAPI\Catalog;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
    /** @var string */
    public $ServiceID = '';
    /** @var string */
    public $ServiceName = '';
    /** @var string */
    public $ServiceAddress = '';
    /** @var string[] */
    public $ServiceTags = array();
    /** @var int */
    public $ServicePort = 0;
    /** @var bool */
    public $ServiceEnableTagOverride = false;

    /**
     * @return string
     */
    public function getServiceID()
    {
        return $this->ServiceID;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->ServiceName;
    }

    /**
     * @return string
     */
    public function getServiceAddress()
    {
        return $this->ServiceAddress;
    }

    /**
     * @return string[]
     */
    public function getServiceTags()
    {
        return $this->ServiceTags;
    }

    /**
     * @return int
     */
    public function getServicePort()
    {
        return $this->ServicePort;
    }

    /**
     * @return boolean
     */
    public function isServiceEnableTagOverride()
    {
        return $this->ServiceEnableTagOverride;
    }
}