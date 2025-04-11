<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

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

use DCarbone\PHPConsulAPI\AbstractModel;

final class AgentCheckUpdate extends AbstractModel
{
    public string $Status;
    public string $Output;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        string $Status = '',
        string $Output = ''
    ) {
        $this->Status = $Status;
        $this->Output = $Output;
        if (null !== $data && [] !== $data) {
            self::jsonUnserialize((object)$data, $this);
        }
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $status): self
    {
        $this->Status = $status;
        return $this;
    }

    public function getOutput(): string
    {
        return $this->Output;
    }

    public function setOutput(string $output): self
    {
        $this->Output = $output;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach ($decoded as $k => $v) {
            $n->{$k} = $v;
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        $out->Status = $this->Status;
        $out->Output = $this->Output;
        return $out;
    }

    public function __toString(): string
    {
        return sprintf('%s: %s', $this->Status, $this->Output);
    }
}
