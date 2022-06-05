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
 * Class TransparentProxyConfig
 */
class TransparentProxyConfig extends AbstractModel
{
    protected const FIELDS = [
        self::FIELD_OUTBOUND_LISTENER_PORT => Transcoding::OMITEMPTY_STRING_FIELD,
        self::FIELD_DIALED_DIRECTLY        => Transcoding::OMITEMPTY_BOOLEAN_FIELD,
    ];

    private const FIELD_OUTBOUND_LISTENER_PORT = 'OutboundListenerPort';
    private const FIELD_DIALED_DIRECTLY        = 'DialedDirectly';

    /** @var int */
    public int $OutboundListenerPort = 0;
    /** @var bool */
    public bool $DialedDirectly = false;

    /**
     * @return int
     */
    public function getOutboundListenerPort(): int
    {
        return $this->OutboundListenerPort;
    }

    /**
     * @param int $OutboundListenerPort
     * @return TransparentProxyConfig
     */
    public function setOutboundListenerPort(int $OutboundListenerPort): self
    {
        $this->OutboundListenerPort = $OutboundListenerPort;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDialedDirectly(): bool
    {
        return $this->DialedDirectly;
    }

    /**
     * @param bool $DialedDirectly
     * @return TransparentProxyConfig
     */
    public function setDialedDirectly(bool $DialedDirectly): self
    {
        $this->DialedDirectly = $DialedDirectly;
        return $this;
    }
}
