<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Transcoding;

class ServiceTxnOp extends AbstractType
{
    protected const FIELDS = [
        self::FIELD_SERVICE => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => AgentService::class,
        ],
    ];

    private const FIELD_SERVICE = 'Service';

    public string $Verb = '';
    public string $Node = '';
    public AgentService $Service;

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        $this->Service = new AgentService(null);
    }

    public function getVerb(): string
    {
        return $this->Verb;
    }

    public function setVerb(string $Verb): self
    {
        $this->Verb = $Verb;
        return $this;
    }

    public function getNode(): string
    {
        return $this->Node;
    }

    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getService(): AgentService
    {
        return $this->Service;
    }

    public function setService(AgentService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }
}
