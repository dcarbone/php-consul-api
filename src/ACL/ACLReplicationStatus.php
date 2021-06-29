<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ACLReplicationStatus
 */
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

    /** @var bool */
    public bool $Enabled = false;
    /** @var bool */
    public bool $Running = false;
    /** @var string */
    public string $SourceDatacenter = '';
    /** @var int */
    public int $ReplicatedIndex = 0;
    /** @var int */
    public int $ReplicatedRoleIndex = 0;
    /** @var int */
    public int $ReplicatedTokenIndex = 0;
    /** @var \DCarbone\Go\Time\Time */
    public Time\Time $LastSuccess;
    /** @var \DCarbone\Go\Time\Time */
    public Time\Time $LastError;

    /**
     * ACLReplicationStatus constructor.
     * @param array|null $data
     */
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
     * @return int
     */
    public function getReplicatedRoleIndex(): int
    {
        return $this->ReplicatedRoleIndex;
    }

    /**
     * @return int
     */
    public function getReplicatedTokenIndex(): int
    {
        return $this->ReplicatedTokenIndex;
    }

    /**
     * @return \DCarbone\Go\Time\Time
     */
    public function getLastSuccess(): Time\Time
    {
        return $this->LastSuccess;
    }

    /**
     * @return \DCarbone\Go\Time\Time
     */
    public function getLastError(): Time\Time
    {
        return $this->LastError;
    }
}
