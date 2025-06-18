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

use DCarbone\PHPConsulAPI\AbstractModel;

class GatewayServiceTLSConfig extends AbstractModel
{
    public null|GatewayTLSSDSConfig $SDS;

    public function __construct(null|GatewayTLSSDSConfig $SDS = null)
    {
        $this->SDS = $SDS;
    }

    public function getSDS(): null|GatewayTLSSDSConfig
    {
        return $this->SDS;
    }

    public function setSDS(null|GatewayTLSSDSConfig $SDS): self
    {
        $this->SDS = $SDS;
        return $this;
    }

    public static function jsonUnserialize(\stdClass $decoded, null|self $into = null): self
    {
        $n = $into ?? new self();
        foreach($decoded as $k => $v) {
            if ('SDS' === $k) {
                $n->SDS = GatewayTLSSDSConfig::jsonUnserialize($v);
            } else {
                $n->{$k} = $v;
            }
        }
        return $n;
    }

    public function jsonSerialize(): \stdClass
    {
        $out = new \stdClass();
        foreach($this->_getDynamicFields() as $k => $v) {
            $out->{$k} = $v;
        }
        if (null !== $this->SDS) {
            $out->SDS = $this->SDS->jsonSerialize();
        }
        return $out;
    }
}