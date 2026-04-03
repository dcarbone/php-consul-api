<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Txn;

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

use DCarbone\PHPConsulAPI\Agent\AgentService;
use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class ServiceTxnOp extends AbstractType
{
    public ServiceOp $Verb;
    public string $Node;
    public AgentService $Service;

    public function __construct(
        string|ServiceOp $Verb = ServiceOp::UNDEFINED,
        string $Node = '',
        null|AgentService $Service = null,
    ) {
        $this->setVerb($Verb);
        $this->Node = $Node;
        $this->Service = $Service ?? new AgentService();
    }

    public function getVerb(): ServiceOp
    {
        return $this->Verb;
    }

    public function setVerb(string|ServiceOp $Verb): self
    {
        if (is_string($Verb)) {
            $Verb = ServiceOp::from($Verb);
        }
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

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            if ('Verb' === $k) {
                $n->setVerb($v);
            } elseif ('Service' === $k) {
                $n->Service = AgentService::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Verb = $this->Verb;
        $out->Node = $this->Node;
        $out->Service = $this->Service;
        return $out;
    }
}
