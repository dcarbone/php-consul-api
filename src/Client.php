<?php namespace DCarbone\PHPConsulAPI;

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

use DCarbone\PHPConsulAPI\Agent\AgentClient;
use DCarbone\PHPConsulAPI\Catalog\CatalogClient;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\Status\StatusClient;

/**
 * Class Client
 * @package DCarbone\PHPConsulAPI
 */
class Client
{
    /** @var KVClient */
    private $_KV;
    /** @var AgentClient */
    private $_Agent;
    /** @var CatalogClient */
    private $_Catalog;
    /** @var StatusClient */
    private $_Status;

    /**
     * Client constructor.
     * @param Config $config
     */
    public function __construct(Config $config = null)
    {
        if (null === $config)
        {
            $config = Config::newDefaultConfig();
        }
        else
        {
            $def = Config::newDefaultConfig();

            if ('' === $config->getAddress())
                $config->setAddress($def->getAddress());

            if ('' === $config->getScheme())
                $config->setScheme($def->getScheme());
        }

        $this->_KV = new KVClient($config);
        $this->_Agent = new AgentClient($config);
        $this->_Catalog = new CatalogClient($config);
        $this->_Status = new StatusClient($config);
    }

    /**
     * @return KVClient
     */
    public function KV()
    {
        return $this->_KV;
    }

    /**
     * @return AgentClient
     */
    public function Agent()
    {
        return $this->_Agent;
    }

    /**
     * @return CatalogClient
     */
    public function Catalog()
    {
        return $this->_Catalog;
    }

    /**
     * @return StatusClient
     */
    public function Status()
    {
        return $this->_Status;
    }

}