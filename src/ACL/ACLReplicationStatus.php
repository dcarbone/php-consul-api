<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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
 * Class ACLReplicationStatus
 * @package DCarbone\PHPConsulAPI\ACL
 */
class ACLReplicationStatus extends AbstractModel
{
    /** @var bool */
    public $Enabled = false;
    /** @var bool */
    public $Running = false;
    /** @var string */
    public $SourceDatacenter = '';
    /** @var int */
    public $ReplicatedIndex = 0;
    /** @var null|string */
    public $LastSuccess = null;
    /** @var null|string */
    public $LastError = null;

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->Enabled;
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->Running;
    }

    /**
     * @return string
     */
    public function getSourceDatacenter(): string
    {
        return $this->SourceDatacenter;
    }

    /**
     * @return int
     */
    public function getReplicatedIndex(): int
    {
        return $this->ReplicatedIndex;
    }

    /**
     * @return null|string
     */
    public function getLastSuccess()
    {
        return $this->LastSuccess;
    }

    /**
     * @return null|string
     */
    public function getLastError()
    {
        return $this->LastError;
    }
}
