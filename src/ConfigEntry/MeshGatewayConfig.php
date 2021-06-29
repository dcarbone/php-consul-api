<?php declare(strict_types=1);

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
 * Class MeshGatewayConfig
 */
class MeshGatewayConfig extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_MODE => Transcoding::OMITEMPTY_STRING_FIELD,
    ];

    private const FIELD_MODE = 'Mode';

    /** @var string */
    public string $Mode = '';

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->Mode;
    }

    /**
     * @param string $mode
     * @return \DCarbone\PHPConsulAPI\ConfigEntry\MeshGatewayConfig
     */
    public function setMode(string $mode): self
    {
        $this->Mode = $mode;
        return $this;
    }
}
