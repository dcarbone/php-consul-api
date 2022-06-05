<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\PreparedQuery;

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

/**
 * Class QueryDatacenterOptions
 */
class QueryDatacenterOptions extends AbstractModel
{
    /** @var int */
    public int $NearestN = 0;
    /** @var string[] */
    public array $Datacenters = [];

    /**
     * @return int
     */
    public function getNearestN(): int
    {
        return $this->NearestN;
    }

    /**
     * @param int $nearestN
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions
     */
    public function setNearestN(int $nearestN): self
    {
        $this->NearestN = $nearestN;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getDatacenters(): array
    {
        return $this->Datacenters;
    }

    /**
     * @param string[] $datacenters
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions
     */
    public function setDatacenters(array $datacenters): self
    {
        $this->Datacenters = $datacenters;
        return $this;
    }

    /**
     * @param string $datacenter
     * @return \DCarbone\PHPConsulAPI\PreparedQuery\QueryDatacenterOptions
     */
    public function addDatacenter(string $datacenter): self
    {
        $this->Datacenters[] = $datacenter;
        return $this;
    }
}
