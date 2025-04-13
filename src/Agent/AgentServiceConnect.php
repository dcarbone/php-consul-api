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

class AgentServiceConnect extends AbstractModel
{
    public bool $Native;
    public null|AgentServiceRegistration $SidecarService;

    public function __construct(
        null|array $data = null, // Deprecated, will be removed.
        bool $Native = false,
        null|AgentServiceRegistration $SidecarService = null,
    ) {
        $this->Native = $Native;
        $this->SidecarService = $SidecarService;
        if (null !== $data && [] !== $data) {
            $this->jsonUnserialize((object)$data, $this);
        }
    }

    public function isNative(): bool
    {
        return $this->Native;
    }

    public function setNative(bool $Native): self
    {
        $this->Native = $Native;
        return $this;
    }

    public function getSidecarService(): null|AgentServiceRegistration
    {
        return $this->SidecarService;
    }

    public function setSidecarService(AgentServiceRegistration $SidecarService): self
    {
        $this->SidecarService = $SidecarService;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): static
    {
        $n = $into ?? new static();
        foreach ($decoded as $k => $v) {
            if ('SidecarService' === $k) {
                $n->SidecarService = AgentServiceRegistration::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach ($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        if ($this->Native) {
            $out->Native = $this->Native;
        }
        if (null !== $this->SidecarService) {
            $out->SidecarService = $this->SidecarService;
        }
        return $out;
    }
}
