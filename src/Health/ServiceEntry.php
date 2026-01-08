<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Health;

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
use DCarbone\PHPConsulAPI\Catalog\Node;

class ServiceEntry extends AbstractType
{
    protected const FIELDS = [
        self::FIELD_NODE    => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => Node::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_SERVICE => [
            Transcoding::FIELD_TYPE     => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS    => AgentService::class,
            Transcoding::FIELD_NULLABLE => true,
        ],
        self::FIELD_CHECKS  => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => HealthChecks::class,
        ],
    ];

    private const FIELD_NODE    = 'Node';
    private const FIELD_SERVICE = 'Service';
    private const FIELD_CHECKS  = 'Checks';

    public ?Node $Node;
    public ?AgentService $Service;
    public HealthChecks $Checks;

    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Checks)) {
            $this->Checks = new HealthChecks(null);
        }
    }

    public function getNode(): ?Node
    {
        return $this->Node;
    }

    public function setNode(?Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getService(): ?AgentService
    {
        return $this->Service;
    }

    public function setService(?AgentService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getChecks(): HealthChecks
    {
        return $this->Checks;
    }

    public function setChecks(HealthChecks $Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }
}
