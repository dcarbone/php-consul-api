<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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
 * Class Weights
 */
class Weights extends AbstractModel
{
    /** @var int */
    public int $Passing = 0;
    /** @var int */
    public int $Warning = 0;

    /**
     * @return int
     */
    public function getPassing(): int
    {
        return $this->Passing;
    }

    /**
     * @param int $Passing
     * @return \DCarbone\PHPConsulAPI\Catalog\Weights
     */
    public function setPassing(int $Passing): self
    {
        $this->Passing = $Passing;
        return $this;
    }

    /**
     * @return int
     */
    public function getWarning(): int
    {
        return $this->Warning;
    }

    /**
     * @param int $Warning
     * @return \DCarbone\PHPConsulAPI\Catalog\Weights
     */
    public function setWarning(int $Warning): self
    {
        $this->Warning = $Warning;
        return $this;
    }
}
