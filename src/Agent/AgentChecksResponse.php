<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\AbstractValuedResponse;
use DCarbone\PHPConsulAPI\Error;

/**
 * Class AgentChecksResponse
 * @package DCarbone\PHPConsulAPI\Agent
 */
class AgentChecksResponse extends AbstractValuedResponse
{
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentCheck[]|null */
    public $Checks = null;

    /**
     * AgentChecksResponse constructor.
     * @param array|null $checks
     * @param \DCarbone\PHPConsulAPI\Error|null $err
     */
    public function __construct(?array $checks, ?Error $err)
    {
        if (null !== $checks) {
            $this->Checks = [];
            foreach ($checks as $k => $v) {
                $this->Checks[$k] = new AgentCheck($v);
            }
        }
        parent::__construct($err);
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentCheck[]|null
     */
    public function getValue(): ?array
    {
        return $this->Checks;
    }
}