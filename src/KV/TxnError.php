<?php

declare(strict_types=1);

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

/**
 * Class TxnError
 */
class TxnError extends AbstractModel
{
    /** @var int */
    public int $OpIndex = 0;
    /** @var string */
    public string $What = '';

    /**
     * @return int
     */
    public function getOpIndex(): int
    {
        return $this->OpIndex;
    }

    /**
     * @param int $OpIndex
     * @return \DCarbone\PHPConsulAPI\KV\TxnError
     */
    public function setOpIndex(int $OpIndex): self
    {
        $this->OpIndex = $OpIndex;
        return $this;
    }

    /**
     * @return string
     */
    public function getWhat(): string
    {
        return $this->What;
    }

    /**
     * @param string $What
     * @return \DCarbone\PHPConsulAPI\KV\TxnError
     */
    public function setWhat(string $What): self
    {
        $this->What = $What;
        return $this;
    }
}
