<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ConfigEntry;

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
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ServiceSplit
 */
class ServiceSplit extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_SERVICE        => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_SERVICE_SUBSET => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_NAMESPACE      => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_SERVICE        = 'Service';
    private const FIELD_SERVICE_SUBSET = 'ServiceSubset';
    private const FIELD_NAMESPACE      = 'Namespace';

    /** @var float */
    public float $Weight = 0.0;
    /** @var string */
    public string $Service = '';
    /** @var string */
    public string $ServiceSubset = '';
    /** @var string */
    public string $Namespace = '';

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->Weight;
    }

    /**
     * @param float $Weight
     * @return ServiceSplit
     */
    public function setWeight(float $Weight): self
    {
        $this->Weight = $Weight;
        return $this;
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->Service;
    }

    /**
     * @param string $Service
     * @return ServiceSplit
     */
    public function setService(string $Service): self
    {
        $this->Service = $Service;
        return $this;
    }

    /**
     * @return string
     */
    public function getServiceSubset(): string
    {
        return $this->ServiceSubset;
    }

    /**
     * @param string $ServiceSubset
     * @return ServiceSplit
     */
    public function setServiceSubset(string $ServiceSubset): self
    {
        $this->ServiceSubset = $ServiceSubset;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $Namespace
     * @return ServiceSplit
     */
    public function setNamespace(string $Namespace): self
    {
        $this->Namespace = $Namespace;
        return $this;
    }
}
