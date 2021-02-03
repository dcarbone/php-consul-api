<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class AutopilotZone
 * @package DCarbone\PHPConsulAPI\Operator
 */
class AutopilotZone extends AbstractModel
{
    private const FIELD_SERVERS = 'Servers';
    private const FIELD_VOTERS  = 'Voters';

    /** @var string[] */
    public array $Servers = [];
    /** @var string[] */
    public array $Voters = [];
    /** @var int */
    public int $FailureTolerance = 0;

    /** @var array[] */
    protected static array $fields = [
        self::FIELD_SERVERS => Hydration::HYDRATE_ARRAY_STRING,
        self::FIELD_VOTERS  => Hydration::HYDRATE_ARRAY_STRING,
    ];

    /**
     * @return string[]
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    /**
     * @return string[]
     */
    public function getVoters(): array
    {
        return $this->Voters;
    }

    /**
     * @return int
     */
    public function getFailureTolerance(): int
    {
        return $this->FailureTolerance;
    }
}