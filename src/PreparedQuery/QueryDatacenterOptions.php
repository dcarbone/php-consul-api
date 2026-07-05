<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class QueryDatacenterOptions extends AbstractType
{
    public int $NearestN;
    /** @var array<string> */
    public array $Datacenters;

    /**
     * @param array<string> $Datacenters
     */
    public function __construct(
        int $NearestN = 0,
        array $Datacenters = [],
    ) {
        $this->NearestN = $NearestN;
        $this->setDatacenters(...$Datacenters);
    }

    public function getNearestN(): int
    {
        return $this->NearestN;
    }

    public function setNearestN(int $NearestN): self
    {
        $this->NearestN = $NearestN;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getDatacenters(): array
    {
        return $this->Datacenters;
    }

    public function setDatacenters(string ...$Datacenters): self
    {
        $this->Datacenters = $Datacenters;
        return $this;
    }

    public function addDatacenter(string $datacenter): self
    {
        $this->Datacenters[] = $datacenter;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ((array)$decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->NearestN = $this->NearestN;
        $out->Datacenters = $this->Datacenters;
        return $out;
    }
}
