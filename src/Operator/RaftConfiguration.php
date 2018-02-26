<?php namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\AbstractModel;

/**
 * Class RaftConfiguration
 * @package DCarbone\PHPConsulAPI\Operator
 */
class RaftConfiguration extends AbstractModel {
    /** @var \DCarbone\PHPConsulAPI\Operator\RaftServer[] */
    public $Servers = [];
    /** @var int */
    public $Index = 0;

    /**
     * RaftConfiguration constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);

        if (0 < count($this->Servers)) {
            $this->Servers = array_filter($this->Servers);

            if (0 < ($cnt = count($this->Servers))) {
                for ($i = 0; $i < $cnt; $i++) {
                    if (!($this->Servers[$i] instanceof RaftServer)) {
                        $this->Servers[$i] = new RaftServer((array)$this->Servers[$i]);
                    }
                }
            }
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\RaftServer[]
     */
    public function getServers(): array {
        return $this->Servers;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\RaftServer[] $Servers
     * @return \DCarbone\PHPConsulAPI\Operator\RaftConfiguration
     */
    public function setServers(array $Servers): RaftConfiguration {
        $this->Servers = [];
        foreach ($Servers as $Server) {
            $this->addServer($Server);
        }
        return $this;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\RaftServer $Server
     * @return \DCarbone\PHPConsulAPI\Operator\RaftConfiguration
     */
    public function addServer(RaftServer $Server): RaftConfiguration {
        $this->Servers[] = $Server;
        return $this;
    }

    /**
     * @return int
     */
    public function getIndex(): int {
        return $this->Index;
    }

    /**
     * @param int $Index
     * @return \DCarbone\PHPConsulAPI\Operator\RaftConfiguration
     */
    public function setIndex(int $Index): RaftConfiguration {
        $this->Index = $Index;
        return $this;
    }
}