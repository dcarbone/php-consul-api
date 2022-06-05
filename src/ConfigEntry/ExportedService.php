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
 * Class ExportedService
 */
class ExportedService extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_NAMESPACE => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_CONSUMERS => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS      => ServiceConsumer::class,
        ]
    ];

    private const FIELD_NAMESPACE = 'Namespace';
    private const FIELD_CONSUMERS = 'Consumers';

    /** @var string  */
    public string $Name = '';
    /** @var string  */
    public string $Namespace;
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\ServiceConsumer[] */
    public array $Consumers = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return ExportedService
     */
    public function setName(string $Name): ExportedService
    {
        $this->Name = $Name;
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
     * @return ExportedService
     */
    public function setNamespace(string $Namespace): ExportedService
    {
        $this->Namespace = $Namespace;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ServiceConsumer[]
     */
    public function getConsumers(): array
    {
        return $this->Consumers;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\ServiceConsumer[] $Consumers
     * @return ExportedService
     */
    public function setConsumers(array $Consumers): ExportedService
    {
        $this->Consumers = $Consumers;
        return $this;
    }
}
