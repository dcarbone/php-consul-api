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
    public null|Node $Node;
    public null|AgentService $Service;
    public HealthChecks $Checks;

    public function __construct(
        null|Node $Node = null,
        null|AgentService $Service = null,
        null|HealthChecks $Checks = null,
    ) {
        $this->Node = $Node;
        $this->Service = $Service;
        $this->setChecks($Checks);
    }

    public function getNode(): null|Node
    {
        return $this->Node;
    }

    public function setNode(null|Node $Node): self
    {
        $this->Node = $Node;
        return $this;
    }

    public function getService(): null|AgentService
    {
        return $this->Service;
    }

    public function setService(null|AgentService $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getChecks(): HealthChecks
    {
        return $this->Checks;
    }

    public function setChecks(null|HealthChecks $Checks): self
    {
        if (null === $Checks) {
            $Checks = new HealthChecks();
        }
        $this->Checks = $Checks;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Node' === $k) {
                $n->Node = new Node($v);
            } elseif ('Service' === $k) {
                $n->Service = new AgentService($v);
            } elseif ('Checks' === $k) {
                $n->Checks = new HealthChecks();
                foreach ($v as $vv) {
                    $n->Checks[] = new HealthCheck($vv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Node = $this->Node;
        $out->Service = $this->Service;
        $out->Checks = $this->Checks;
        return $out;
    }
}
