<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

class ACLReplicationStatus extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_LAST_SUCCESS => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_TIME,
        ],
        self::FIELD_LAST_ERROR   => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_TIME,
        ],
    ];

    private const FIELD_LAST_SUCCESS = 'LastSuccess';
    private const FIELD_LAST_ERROR   = 'LastError';

    public bool $Enabled = false;
    public bool $Running = false;
    public string $SourceDatacenter = '';
    public int $ReplicatedIndex = 0;
    public int $ReplicatedRoleIndex = 0;
    public int $ReplicatedTokenIndex = 0;
    public Time\Time $LastSuccess;
    public Time\Time $LastError;

    public function __construct(?array $data = null)
    {
        parent::__construct($data);
        if (!isset($this->LastSuccess)) {
            $this->LastSuccess = Time::New();
        }
        if (!isset($this->LastError)) {
            $this->LastError = Time::New();
        }
    }

    public function isEnabled(): bool
    {
        return $this->Enabled;
    }

    public function isRunning(): bool
    {
        return $this->Running;
    }

    public function getSourceDatacenter(): string
    {
        return $this->SourceDatacenter;
    }

    public function getReplicatedIndex(): int
    {
        return $this->ReplicatedIndex;
    }

    public function getReplicatedRoleIndex(): int
    {
        return $this->ReplicatedRoleIndex;
    }

    public function getReplicatedTokenIndex(): int
    {
        return $this->ReplicatedTokenIndex;
    }

    public function getLastSuccess(): Time\Time
    {
        return $this->LastSuccess;
    }

    public function getLastError(): Time\Time
    {
        return $this->LastError;
    }
}
