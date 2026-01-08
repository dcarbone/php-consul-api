<?php

declare(strict_types=1);

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

namespace DCarbone\PHPConsulAPI\Session;

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class ServiceCheck extends AbstractType
{
    public string $ID = '';
    public string $Namespace = '';

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $id): self
    {
        $this->ID = $id;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->Namespace = $namespace;
        return $this;
    }
}
