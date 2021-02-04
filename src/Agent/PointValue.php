<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class PointValue
 * @package DCarbone\PHPConsulAPI\Agent
 */
class PointValue extends AbstractModel
{
    /** @var string */
    public string $Name = '';
    /** @var array */
    public array $Points = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return PointValue
     */
    public function setName(string $Name): PointValue
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return array
     */
    public function getPoints(): array
    {
        return $this->Points;
    }

    /**
     * @param array $Points
     * @return PointValue
     */
    public function setPoints(array $Points): PointValue
    {
        $this->Points = $Points;
        return $this;
    }
}