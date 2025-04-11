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
    private const FIELD_DATACENTERS = 'Datacenters';

    public string $ServiceName;
    public array $Datacenters;

    public function __construct(
        array $data = [], // Deprecated, will be removed.
        string $ServiceName = '',
        iterable $Datacenters = []
    ) {
        if ([] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
            return;
        }
        $this->ServiceName = $ServiceName;
        $this->Datacenters = $Datacenters;
    }

    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    public function getDatacenters(): array
    {
        return $this->Datacenters;
    }

    public function setDatacenters(string ...$datacenters): self
    {
        $this->Datacenters = $datacenters;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new static();
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
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->ServiceName = $this->ServiceName;
        if ([] !== $this->Datacenters) {
            $out->Datacenters = $this->Datacenters;
        }
        return $out;
    }
}
