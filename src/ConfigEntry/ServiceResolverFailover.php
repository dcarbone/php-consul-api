<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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

class ServiceResolverFailover extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SERVICE        => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_SERVICE_SUBSET => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAMESPACE      => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_DATACENTERS    => Transcoding::OMITEMPTY_STRING_ARRAY_FIELD,
    ];

    private const FIELD_SERVICE        = 'Service';
    private const FIELD_SERVICE_SUBSET = 'ServiceSubset';
    private const FIELD_NAMESPACE      = 'Namespace';
    private const FIELD_DATACENTERS    = 'Datacenters';

    public string $Service;
    public string $ServiceSubset;
    public string $Namespace;
    public array $Datacenters;

    public function getService(): string
    {
        return $this->Service;
    }

    public function setService(string $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    public function getServiceSubset(): string
    {
        return $this->ServiceSubset;
    }

    public function setServiceSubset(string $ServiceSubset): self
    {
        $this->ServiceSubset = $ServiceSubset;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    public function getDatacenters(): array
    {
        return $this->Datacenters;
    }

    public function setDatacenters(array $Datacenters): self
    {
        $this->Datacenters = $Datacenters;
        return $this;
    }
}
