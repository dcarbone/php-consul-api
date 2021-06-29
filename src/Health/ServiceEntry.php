<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Health;

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
use DCarbone\PHPConsulAPI\Catalog\Node;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ServiceEntry
 */
class ServiceEntry extends AbstractModel
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

    /** @var \DCarbone\PHPConsulAPI\Catalog\Node|null */
    public ?Node $Node = null;
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService|null */
    public ?AgentService $Service = null;
    /** @var \DCarbone\PHPConsulAPI\Health\HealthChecks */
    public HealthChecks $Checks;

    /**
     * ServiceEntry constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        if (!isset($this->Checks)) {
            $this->Checks = new HealthChecks(null);
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Catalog\Node|null
     */
    public function getNode(): ?Node
    {
        return $this->Node;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Catalog\Node|null $Node
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntry
     */
    public function setNode(?Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService|null
     */
    public function getService(): ?AgentService
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentService|null $Service
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntry
     */
    public function setService(?AgentService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Health\HealthChecks
     */
    public function getChecks(): HealthChecks
    {
        return $this->Checks;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Health\HealthChecks $Checks
     * @return \DCarbone\PHPConsulAPI\Health\ServiceEntry
     */
    public function setChecks(HealthChecks $Checks): self
    {
        $this->Checks = $Checks;
        return $this;
    }
}
