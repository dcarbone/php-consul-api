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

class AutopilotZone extends AbstractType
{
    /** @var array<string> */
    public array $Servers;
    /** @var array<string> */
    public array $Voters;
    public int $FailureTolerance;

    /**
     * @param array<string> $Servers
     * @param array<string> $Voters
     * @param null|array $data Deprecated: constructor hydration via $data; use self::jsonUnserialize instead.
     */
    public function __construct(
        null|array $data = null,
        array $Servers = [],
        array $Voters = [],
        int $FailureTolerance = 0,
    ) {
        if (null !== $data) {
            self::_hydrateFromDecoded((object)$data, $this);
            return;
        }
        $this->setServers(...$Servers);
        $this->setVoters(...$Voters);
        $this->FailureTolerance = $FailureTolerance;
    }

    /**
     * @return array<string>
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    public function setServers(string ...$Servers): self
    {
        $this->Servers = $Servers;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getVoters(): array
    {
        return $this->Voters;
    }

    public function setVoters(string ...$Voters): self
    {
        $this->Voters = $Voters;
        return $this;
    }

    public function getFailureTolerance(): int
    {
        return $this->FailureTolerance;
    }

    public function setFailureTolerance(int $FailureTolerance): self
    {
        $this->FailureTolerance = $FailureTolerance;
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
            $n->{$k} = $v;
        }
    }

    public function jsonSerialize(): \stdClass
    {
        $out = $this->_startJsonSerialize();
        $out->Servers = $this->Servers;
        $out->Voters = $this->Voters;
        $out->FailureTolerance = $this->FailureTolerance;
        return $out;
    }
}
