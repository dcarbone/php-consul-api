<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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

use DCarbone\PHPConsulAPI\AbstractModel;
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Transcoding;

class CatalogNode extends AbstractModel
{
    public const FIELDS = [
        self::FIELD_NODE     => [
            Transcoding::FIELD_TYPE  => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS => Node::class,
        ],
        self::FIELD_SERVICES => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_CLASS      => AgentService::class,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
        ],
    ];

    private const FIELD_NODE     = 'Node';
    private const FIELD_SERVICES = 'Services';

    public ?Node $Node = null;
    public array $Services = [];

    public function getNode(): ?Node
    {
        return $this->Node;
    }

    public function setNode(?Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getServices(): array
    {
        return $this->Services;
    }

    public function setServices(array $Services): self
    {
        $this->Services = $Services;
        return $this;
    }
}
