<?php namespace DCarbone\PHPConsulAPI\PreparedQuery;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * @package DCarbone\PHPConsulAPI\PreparedQuery
 */
class QueryDatacenterOptions extends AbstractModel
{
    /** @var int */
    public $NearestN = 0;
    /** @var string[] */
    public $Datacenters = [];

    /**
     * @return int
     */
    public function getNearestN()
    {
        return $this->NearestN;
    }

    /**
     * @param int $NearestN
     * @return QueryDatacenterOptions
     */
    public function setNearestN($NearestN)
    {
        $this->NearestN = $NearestN;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getDatacenters()
    {
        return $this->Datacenters;
    }

    /**
     * @param string[] $Datacenters
     * @return QueryDatacenterOptions
     */
    public function setDatacenters(array $Datacenters)
    {
        $this->Datacenters = [];
        foreach($Datacenters as $datacenter)
        {
            $this->addDatacenter($datacenter);
        }
        return $this;
    }

    public function addDatacenter($datacenter)
    {
        $this->Datacenters[] = $datacenter;
        return $this;
    }
}