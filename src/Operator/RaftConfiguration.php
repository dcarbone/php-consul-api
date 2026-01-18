<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;
use DCarbone\PHPConsulAPI\Transcoding;

class RaftConfiguration extends AbstractType
{
    protected const FIELDS = [
        self::FIELD_SERVERS => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => RaftServer::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
    ];

    private const FIELD_SERVERS = 'Servers';

    public array $Servers;
    public int $Index;

    public function getServers(): array
    {
        return $this->Servers;
    }

    public function setServers(array $Servers): self
    {
        $this->Servers = $Servers;
        return $this;
    }

    public function getIndex(): int
    {
        return $this->Index;
    }

    public function setIndex(int $Index): self
    {
        $this->Index = $Index;
        return $this;
    }
}
