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
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\Transcoding;

/**
 * Class ServiceSplitterConfigEntry
 */
class ServiceSplitterConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    protected const FIELDS = ConfigEntry::INTERFACE_FIELDS + [
        self::FIELD_SPLITS => [
            Transcoding::FIELD_TYPE       => Transcoding::ARRAY,
            Transcoding::FIELD_ARRAY_TYPE => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS      => ServiceSplit::class,
            Transcoding::FIELD_OMITEMPTY  => true,
        ],
    ];

    private const FIELD_SPLITS = 'Splits';

    /** @var string */
    public string $Kind = Consul::ServiceSplitter;
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplit[] */
    public array $Splits = [];

    /**
     * @return string
     */
    public function getKind(): string
    {
        return Consul::ServiceSplitter;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplit[]
     */
    public function getSplits(): array
    {
        return $this->Splits;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\ServiceSplit[] $Splits
     * @return ServiceSplitterConfigEntry
     */
    public function setSplits(array $Splits): self
    {
        $this->Splits = $Splits;
        return $this;
    }
}
