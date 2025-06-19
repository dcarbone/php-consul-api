<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

use DCarbone\Go\Time;
use DCarbone\PHPConsulAPI\ConfigEntry;

function dur_to_millisecond(Time\Duration $dur): string
{
    $ns = $dur->Nanoseconds();
    $ms = $dur->Milliseconds();

    if (0 < $ns && 0 === (int)$ms) {
        $ms = 1;
    }

    return "${ms}ms";
}

$_zeroObject = new \stdclass();

function _enc_obj_if_valued(\stdClass &$out, string $field, \stdClass | \JsonSerializable $obj): void
{
    global $_zeroObject;
    if ($obj instanceof \JsonSerializable) {
        $obj = $obj->jsonSerialize();
    }
    if ($obj != $_zeroObject) {
        $out->{$field} = $obj;
    }
}

function MakeConfigEntry(string $kind, string $name): ConfigEntry\ConfigEntry
{
    switch ($kind) {
        case Consul::ServiceDefaults:
            return new ConfigEntry\ServiceConfigEntry(kind: $kind, name: $name);
        case Consul::ProxyDefaults:
            return new ConfigEntry\ProxyConfigEntry(kind: $kind, name: $name);
        case Consul::ServiceRouter:
            return new ConfigEntry\ServiceRouterConfigEntry(kind: $kind, name: $name);
        case Consul::ServiceSplitter:
            return new ConfigEntry\ServiceSplitterConfigEntry(kind: $kind, name: $name);
        case Consul::ServiceResolver:
            return new ConfigEntry\ServiceResolverConfigEntry(kind: $kind, name: $name);
        case Consul::IngressGateway:
            return new ConfigEntry\IngressGatewayConfigEntry(kind: $kind, name: $name);
        case Consul::TerminatingGateway:
            return new ConfigEntry\TerminatingGatewayConfigEntry(kind: $kind, name: $name);


        default:
            throw new \InvalidArgumentException(sprintf('Unknown kind "%s"', $kind));
    }
}
