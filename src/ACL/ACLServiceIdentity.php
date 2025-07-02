<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

class ACLServiceIdentity extends AbstractModel
{
    public string $ServiceName;
    /** @var string[] */
    public array $Datacenters;

    public function __construct(
        string $ServiceName = '',
        iterable $Datacenters = []
    ) {
        $this->ServiceName = $ServiceName;
        $this->setDatacenters(...$Datacenters);
}

    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    /**
     * @return string[]
     */
    public function getDatacenters(): array
    {
        return $this->Datacenters;
    }

    public function setDatacenters(string ...$datacenters): self
    {
        $this->Datacenters = $datacenters;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            if ('Datacenters' === $k) {
                $n->setDatacenters(...$v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ServiceName = $this->ServiceName;
        if ([] !== $this->Datacenters) {
            $out->Datacenters = $this->Datacenters;
        }
        return $out;
    }
}
