<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class OperatorHealthReply
 */
class OperatorHealthReply extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SERVERS => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => ServerHealth::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
    ];

    private const FIELD_SERVERS = 'Servers';

    /** @var bool */
    public bool $Healthy = false;
    /** @var int */
    public int $FailureTolerance = 0;
    /** @var \DCarbone\PHPConsulAPI\Operator\ServerHealth[] */
    public array $Servers = [];

    /**
     * @return bool
     */
    public function isHealthy(): bool
    {
        return $this->Healthy;
    }

    /**
     * @param bool $Healthy
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorHealthReply
     */
    public function setHealthy(bool $Healthy): self
    {
        $this->Healthy = $Healthy;
        return $this;
    }

    /**
     * @return int
     */
    public function getFailureTolerance(): int
    {
        return $this->FailureTolerance;
    }

    /**
     * @param int $FailureTolerance
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorHealthReply
     */
    public function setFailureTolerance(int $FailureTolerance): self
    {
        $this->FailureTolerance = $FailureTolerance;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Operator\ServerHealth[]
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Operator\ServerHealth[] $Servers
     * @return \DCarbone\PHPConsulAPI\Operator\OperatorHealthReply
     */
    public function setServers(array $Servers): self
    {
        $this->Servers = $Servers;
        return $this;
    }
}
