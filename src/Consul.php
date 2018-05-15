<?php namespace DCarbone\PHPConsulAPI;

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

use DCarbone\PHPConsulAPI\ACL\ACLClient;
use DCarbone\PHPConsulAPI\Agent\AgentClient;
use DCarbone\PHPConsulAPI\Catalog\CatalogClient;
use DCarbone\PHPConsulAPI\Coordinate\CoordinateClient;
use DCarbone\PHPConsulAPI\Event\EventClient;
use DCarbone\PHPConsulAPI\Health\HealthClient;
use DCarbone\PHPConsulAPI\KV\KVClient;
use DCarbone\PHPConsulAPI\Operator\OperatorClient;
use DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryClient;
use DCarbone\PHPConsulAPI\Session\SessionClient;
use DCarbone\PHPConsulAPI\Status\StatusClient;

/**
 * Class Consul
 * @package DCarbone\PHPConsulAPI
 */
class Consul {
    const HTTPAddrEnvName = 'CONSUL_HTTP_ADDR';
    const HTTPTokenEnvName = 'CONSUL_HTTP_TOKEN';
    const HTTPAuthEnvName = 'CONSUL_HTTP_AUTH';
    const HTTPCAFileEnvName = "CONSUL_CACERT";
    const HTTPClientCertEnvName = "CONSUL_CLIENT_CERT";
    const HTTPClientKeyEnvName = "CONSUL_CLIENT_KEY";
    const HTTPSSLEnvName = 'CONSUL_HTTP_SSL';
    const HTTPSSLVerifyEnvName = 'CONSUL_HTTP_SSL_VERIFY';

    const HealthAny = 'any';
    const HealthPassing = 'passing';
    const HealthWarning = 'warning';
    const HealthCritical = 'critical';
    const HealthMaint = 'maintenance';

    const SessionBehaviorRelease = 'release';
    const SessionBehaviorDelete = 'delete';

    /** @var \DCarbone\PHPConsulAPI\ACL\ACLClient */
    public $ACL;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentClient */
    public $Agent;
    /** @var \DCarbone\PHPConsulAPI\Catalog\CatalogClient */
    public $Catalog;
    /** @var \DCarbone\PHPConsulAPI\Coordinate\CoordinateClient */
    public $Coordinate;
    /** @var \DCarbone\PHPConsulAPI\Event\EventClient */
    public $Event;
    /** @var \DCarbone\PHPConsulAPI\Health\HealthClient */
    public $Health;
    /** @var \DCarbone\PHPConsulAPI\KV\KVClient */
    public $KV;
    /** @var \DCarbone\PHPConsulAPI\Operator\OperatorClient */
    public $Operator;
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryClient */
    public $PreparedQuery;
    /** @var \DCarbone\PHPConsulAPI\Session\SessionClient */
    public $Session;
    /** @var \DCarbone\PHPConsulAPI\Status\StatusClient */
    public $Status;

    /**
     * Client constructor.
     * @param Config $config
     */
    public function __construct(Config $config = null) {
        if (null === $config) {
            $config = Config::newDefaultConfig();
        } else {
            $def = Config::newDefaultConfig();

            if ('' === $config->getAddress()) {
                $config->setAddress($def->getAddress());
            }

            if ('' === $config->getScheme()) {
                $config->setScheme($def->getScheme());
            }
        }

        $this->ACL = new ACLClient($config);
        $this->Agent = new AgentClient($config);
        $this->Catalog = new CatalogClient($config);
        $this->Coordinate = new CoordinateClient($config);
        $this->Event = new EventClient($config);
        $this->Health = new HealthClient($config);
        $this->KV = new KVClient($config);
        $this->Operator = new OperatorClient($config);
        $this->PreparedQuery = new PreparedQueryClient($config);
        $this->Session = new SessionClient($config);
        $this->Status = new StatusClient($config);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ACL\ACLClient
     */
    public function ACL() {
        return $this->ACL;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentClient
     */
    public function Agent() {
        return $this->Agent;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogClient
     */
    public function Catalog() {
        return $this->Catalog;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Coordinate\CoordinateClient
     */
    public function Coordinate() {
        return $this->Coordinate;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Event\EventClient
     */
    public function Event() {
        return $this->Event;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthClient
     */
    public function Health() {
        return $this->Health;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\KV\KVClient
     */
    public function KV() {
        return $this->KV;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorClient
     */
    public function Operator() {
        return $this->Operator;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\PreparedQueryClient
     */
    public function PreparedQuery() {
        return $this->PreparedQuery;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Session\SessionClient
     */
    public function Session() {
        return $this->Session;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Status\StatusClient
     */
    public function Status() {
        return $this->Status;
    }
}
