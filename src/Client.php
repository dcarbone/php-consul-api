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

use DCarbone\PHPConsulAPI\Client\Agent;
use DCarbone\PHPConsulAPI\Client\Catalog;
use DCarbone\PHPConsulAPI\Client\Coordinate;
use DCarbone\PHPConsulAPI\Client\Event;
use DCarbone\PHPConsulAPI\Client\Health;
use DCarbone\PHPConsulAPI\Client\KV;
use DCarbone\PHPConsulAPI\Client\Session;
use DCarbone\PHPConsulAPI\Client\Status;

/**
 * Class Client
 * @package DCarbone\PHPConsulAPI
 */
class Client
{
    /** @var \DCarbone\PHPConsulAPI\Client\KV */
    public $KV;
    /** @var \DCarbone\PHPConsulAPI\Client\Agent */
    public $Agent;
    /** @var \DCarbone\PHPConsulAPI\Client\Catalog */
    public $Catalog;
    /** @var \DCarbone\PHPConsulAPI\Client\Status */
    public $Status;
    /** @var \DCarbone\PHPConsulAPI\Client\Event */
    public $Event;
    /** @var \DCarbone\PHPConsulAPI\Client\Health */
    public $Health;
    /** @var \DCarbone\PHPConsulAPI\Client\Coordinate */
    public $Coordinate;
    /** @var \DCarbone\PHPConsulAPI\Client\Session */
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

        $this->KV = new KV($config);
        $this->Agent = new Agent($config);
        $this->Catalog = new Catalog($config);
        $this->Status = new Status($config);
        $this->Event = new Event($config);
        $this->Health = new Health($config);
        $this->Coordinate = new Coordinate($config);
        $this->Session = new Session($config);
    }

    /**
     * @return KV
     */
    public function KV()
    {
        return $this->KV;
    }

    /**
     * @return Agent
     */
    public function Agent()
    {
        return $this->Agent;
    }

    /**
     * @return Catalog
     */
    public function Catalog()
    {
        return $this->Catalog;
    }

    /**
     * @return Status
     */
    public function Status()
    {
        return $this->Status;
    }

    /**
     * @return Event
     */
    public function Event()
    {
        return $this->Event;
    }

    /**
     * @return Health
     */
    public function Health()
    {
        return $this->Health;
    }

    /**
     * @return Coordinate
     */
    public function Coordinate()
    {
        return $this->Coordinate;
    }

    /**
     * @return Session
     */
    public function Session()
    {
        return $this->Session;
    }
}