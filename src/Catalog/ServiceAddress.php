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
 * Class ServiceAddress
 */
class ServiceAddress extends AbstractModel
{
    /** @var string */
    public string $Address = '';
    /** @var int */
    public int $Port = 0;

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->Address;
    }

    /**
     * @param string $address
     * @return \DCarbone\PHPConsulAPI\Catalog\ServiceAddress
     */
    public function setAddress(string $address): self
    {
        $this->Address = $address;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->Port;
    }

    /**
     * @param int $port
     * @return \DCarbone\PHPConsulAPI\Catalog\ServiceAddress
     */
    public function setPort(int $port): self
    {
        $this->Port = $port;
        return $this;
    }
}
