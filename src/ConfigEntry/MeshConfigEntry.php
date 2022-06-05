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
 * Class MeshConfigEntry
 */
class MeshConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    protected const FIELDS = COnfigEntry::INTERFACE_FIELDS + [
        self::FIELD_TRANSPARENT_PROXY => [
            Transcoding::FIELD_TYPE      => Transcoding::OBJECT,
            Transcoding::FIELD_CLASS     => TransparentProxyConfig::class,
            Transcoding::FIELD_NULLABLE  => false,
            Transcoding::FIELD_OMITEMPTY => false,
        ],
    ];

    private const FIELD_TRANSPARENT_PROXY = 'TransparentProxy';

    /** @var string */
    public string $kind = Consul::MeshConfig;
    /** @var \DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyConfig */
    public TransparentProxyConfig $TransparentProxy;

    /**
     * @return string
     */
    public function getKind(): string
    {
        return Consul::MeshConfig;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyConfig
     */
    public function getTransparentProxy(): TransparentProxyConfig
    {
        return $this->TransparentProxy;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\ConfigEntry\TransparentProxyConfig $TransparentProxy
     * @return MeshConfigEntry
     */
    public function setTransparentProxy(TransparentProxyConfig $TransparentProxy): self
    {
        $this->TransparentProxy = $TransparentProxy;
        return $this;
    }
}
