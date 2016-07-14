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

use DCarbone\PHPConsulAPI\Agent\AgentApiClient;
use DCarbone\PHPConsulAPI\Catalog\CatalogApiClient;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateApiClient;
use DCarbone\PHPConsulAPI\Event\EventApiClient;
use DCarbone\PHPConsulAPI\Health\HealthApiClient;
use DCarbone\PHPConsulAPI\KV\KVApiClient;
use DCarbone\PHPConsulAPI\Session\SessionApiClient;
use DCarbone\PHPConsulAPI\Status\StatusApiClient;

/**
 * Class Consul
 * @package DCarbone\PHPConsulAPI
 */
class Consul
{
    /** @var KVApiClient */
    public $KV;
    /** @var AgentApiClient */
    public $Agent;
    /** @var CatalogApiClient */
    public $Catalog;
    /** @var StatusApiClient */
    public $Status;
    /** @var EventApiClient */
    public $Event;
    /** @var HealthApiClient */
    public $Health;
    /** @var CoordinateApiClient */
    public $Coordinate;
    /** @var SessionApiClient */
    public $Session;

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

        $this->KV = new KVApiClient($config);
        $this->Agent = new AgentApiClient($config);
        $this->Catalog = new CatalogApiClient($config);
        $this->Status = new StatusApiClient($config);
        $this->Event = new EventApiClient($config);
        $this->Health = new HealthApiClient($config);
        $this->Coordinate = new CoordinateApiClient($config);
        $this->Session = new SessionApiClient($config);
    }
}