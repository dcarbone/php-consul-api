<?php namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class OperatorHealthReply
 * @package DCarbone\PHPConsulAPI\Operator
 */
class OperatorHealthReply extends AbstractModel {
    /** @var bool */
    public $Healthy = false;
    /** @var int */
    public $FailureTolerance = 0;
    /** @var \DCarbone\PHPConsulAPI\Operator\ServerHealth[] */
    public $Servers = [];

    /**
     * OperatorHealthReply constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        parent::__construct($data);
        foreach (array_filter($this->Servers) as &$server) {
            if (!($server instanceof ServerHealth)) {
                $server = new ServerHealth($server);
            }
        }
    }

    /**
     * @return bool
     */
    public function isHealthy(): bool {
        return $this->Healthy;
    }

    /**
     * @return int
     */
    public function getFailureTolerance(): int {
        return $this->FailureTolerance;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth[]
     */
    public function getServers(): array {
        return $this->Servers;
    }
}