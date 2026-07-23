<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Operator;

/*
   Copyright 2016-2026 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\PHPLib\AbstractType;

class RaftConfiguration extends AbstractType
{
    /** @var array<RaftServer> */
    public array $Servers;
    public int $Index;

    /**
     * @param array<RaftServer> $Servers
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        array $Servers = [],
        int $Index = 0,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->setServers(...$Servers);
        $this->Index = $Index;
    }

    /**
     * @return array<RaftServer>
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    public function setServers(RaftServer ...$Servers): self
    {
        $this->Servers = $Servers;
        return $this;
    }

    public function getIndex(): int
    {
        return $this->Index;
    }

    public function setIndex(int $Index): self
    {
        $this->Index = $Index;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded): self
    {
        $n = new self();
        self::_hydrateFromDecoded($decoded, $n);
        return $n;
    }

    protected static function _hydrateFromDecoded(\stdClass $decoded, self $n): void
    {
        foreach ((array)$decoded as $k => $v) {
            if ('Servers' === $k) {
                $n->Servers = [];
                foreach ($v as $sv) {
                    $n->Servers[] = RaftServer::jsonUnserialize($sv);
                }
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Servers = $this->Servers;
        $out->Index = $this->Index;
        return $out;
    }
}
