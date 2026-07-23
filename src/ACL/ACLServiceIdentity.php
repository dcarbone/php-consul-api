<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\ACL;

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

class ACLServiceIdentity extends AbstractType
{
    public string $ServiceName;
    /** @var string[] */
    public array $Datacenters;

    /**
     * @param array<string> $Datacenters
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        string $ServiceName = '',
        array $Datacenters = []
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->ServiceName = $ServiceName;
        $this->setDatacenters(...$Datacenters);
    }

    public function getServiceName(): string
    {
        return $this->ServiceName;
    }

    public function setServiceName(string $ServiceName): self
    {
        $this->ServiceName = $ServiceName;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getDatacenters(): array
    {
        return $this->Datacenters;
    }

    public function setDatacenters(string ...$Datacenters): self
    {
        $this->Datacenters = $Datacenters;
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
            if ('Datacenters' === $k) {
                $n->setDatacenters(...$v);
            } else {
                $n->{$k} = $v;
            }
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->ServiceName = $this->ServiceName;
        if ([] !== $this->Datacenters) {
            $out->Datacenters = $this->Datacenters;
        }
        return $out;
    }
}
