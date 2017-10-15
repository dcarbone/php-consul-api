<?php namespace DCarbone\PHPConsulAPI\PreparedQuery;

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Health\ServiceEntry;

/**
 * Class PreparedQueryExecuteResponse
 * @package DCarbone\PHPConsulAPI\PreparedQuery
 */
class PreparedQueryExecuteResponse extends AbstractModel {
    /** @var string */
    public $Service = '';
    /** @var \DCarbone\PHPConsulAPI\Health\ServiceEntry[] */
    public $Nodes = [];
    /** @var \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions */
    public $DNS = null;
    /** @var string */
    public $Datacenter = '';
    /** @var int */
    public $Failovers = 0;

    /**
     * PreparedQueryExecuteResponse constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);

        if (null !== $this->DNS && !($this->DNS instanceof QueryDNSOptions)) {
            $this->DNS = new QueryDNSOptions((array)$this->DNS);
        }

        if (0 < count($this->Nodes)) {
            $this->Nodes = array_filter($this->Nodes);
            if (0 < ($cnt = count($this->Nodes))) {
                for ($i = 0; $i < $cnt; $i++) {
                    if (!($this->Nodes[$i] instanceof ServiceEntry)) {
                        $this->Nodes[$i] = new ServiceEntry((array)$this->Nodes[$i]);
                    }

                }
            }
        }
    }

    /**
     * @return string
     */
    public function getService(): string {
        return $this->Service;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntry[]
     */
    public function getNodes(): array {
        return $this->Nodes;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryDNSOptions
     */
    public function getDNS() {
        return $this->DNS;
    }

    /**
     * @return string
     */
    public function getDatacenter(): string {
        return $this->Datacenter;
    }

    /**
     * @return int
     */
    public function getFailovers(): int {
        return $this->Failovers;
    }
}