<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Catalog;

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
use DCarbone\PHPConsulAPI\Transcoding;

class CatalogDeregistration extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_ADDRESS   => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAMESPACE => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_ADDRESS   = 'Address';
    private const FIELD_NAMESPACE = 'Namespace';

    public string $Node = '';
    public string $Address = '';
    public string $Datacenter = '';
    public string $ServiceID = '';
    public string $CheckID = '';

    public function getNode(): string
    {
        return $this->Node;
    }

    public function setNode(string $node): self
    {
        $this->Node = $node;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->Address;
    }

    public function setAddress(string $address): self
    {
        $this->Address = $address;
        return $this;
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $datacenter): self
    {
        $this->Datacenter = $datacenter;
        return $this;
    }

    public function getServiceID(): string
    {
        return $this->ServiceID;
    }

    public function setServiceID(string $serviceID): self
    {
        $this->ServiceID = $serviceID;
        return $this;
    }

    public function getCheckID(): string
    {
        return $this->CheckID;
    }

    public function setCheckID(string $checkID): self
    {
        $this->CheckID = $checkID;
        return $this;
    }
}
