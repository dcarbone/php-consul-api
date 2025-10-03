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

use DCarbone\PHPConsulAPI\PHPLib\Types\AbstractType;

class TransparentProxyConfig extends AbstractType
{
    public int $OutboundListenerPort;
    public bool $DialedDirectly;

    public function __construct(
        null|array $data = [], // Deprecated, will be removed.
        int $OutboundListenerPort = 0,
        bool $DialedDirectly = false
    ) {
        $this->OutboundListenerPort = $OutboundListenerPort;
        $this->DialedDirectly = $DialedDirectly;
}

    public function getOutboundListenerPort(): int
    {
        return $this->OutboundListenerPort;
    }

    public function setOutboundListenerPort(int $OutboundListenerPort): self
    {
        $this->OutboundListenerPort = $OutboundListenerPort;
        return $this;
    }

    public function isDialedDirectly(): bool
    {
        return $this->DialedDirectly;
    }

    public function setDialedDirectly(bool $DialedDirectly): self
    {
        $this->DialedDirectly = $DialedDirectly;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        if (0 !== $this->OutboundListenerPort) {
            $out->OutboundListenerPort = $this->OutboundListenerPort;
        }
        if ($this->DialedDirectly) {
            $out->DialedDirectly = $this->DialedDirectly;
        }
        return $out;
    }
}
