<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\KV;

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
 * Class ServiceTxnOp
 */
class ServiceTxnOp extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SERVICE => [
            Hydration::FIELD_TYPE  => Hydration::OBJECT,
            Hydration::FIELD_CLASS => AgentService::class,
        ],
    ];

    private const FIELD_SERVICE = 'Service';

    /** @var string */
    public string $Verb = '';
    /** @var string */
    public string $Node = '';
    /** @var \DCarbone\PHPConsulAPI\Agent\AgentService */
    public AgentService $Service;

    /**
     * ServiceTxnOp constructor.
     * @param array|null $data
     */
    public function __construct(?array $data = [])
    {
        parent::__construct($data);
        $this->Service = new AgentService(null);
    }

    /**
     * @return string
     */
    public function getVerb(): string
    {
        return $this->Verb;
    }

    /**
     * @param string $Verb
     * @return \DCarbone\PHPConsulAPI\KV\ServiceTxnOp
     */
    public function setVerb(string $Verb): self
    {
        $this->Verb = $Verb;
        return $this;
    }

    /**
     * @return string
     */
    public function getNode(): string
    {
        return $this->Node;
    }

    /**
     * @param string $Node
     * @return \DCarbone\PHPConsulAPI\KV\ServiceTxnOp
     */
    public function setNode(string $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Agent\AgentService
     */
    public function getService(): AgentService
    {
        return $this->Service;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Agent\AgentService $Service
     * @return \DCarbone\PHPConsulAPI\KV\ServiceTxnOp
     */
    public function setService(AgentService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }
}
