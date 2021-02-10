<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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
use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\Hydration;

/**
 * Class CatalogNode
 */
class CatalogNode extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_NODE     => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => Node::class,
        ],
        self::FIELD_SERVICES => [
            Hydration::FIELD_TYPE       => Hydration::ARRAY,
            Hydration::FIELD_CLASS      => AgentService::class,
            Hydration::FIELD_ARRAY_TYPE => Hydration::OBJECT,
        ],
    ];

    private const FIELD_NODE     = 'Node';
    private const FIELD_SERVICES = 'Services';

    /** @var \DCarbone\PHPConsulAPI\Catalog\Node|null */
    public ?Node $Node = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService[] */
    public array $Services = [];

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\Node|null
     */
    public function getNode(): ?Node
    {
        return $this->Node;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\Node|null $Node
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNode
     */
    public function setNode(?Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService[]
     */
    public function getServices(): array
    {
        return $this->Services;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentService[] $Services
     * @return \DCarbone\PHPConsulAPI\Catalog\CatalogNode
     */
    public function setServices(array $Services): self
    {
        $this->Services = $Services;
        return $this;
    }
}
