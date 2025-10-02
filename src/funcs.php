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

function dur_to_millisecond(Time\Duration $dur): string
{
    $ns = $dur->Nanoseconds();
    $ms = $dur->Milliseconds();

    if (0 < $ns && 0 === (int)$ms) {
        $ms = 1;
    }

    return "${ms}ms";
}

$_zeroObject = new \stdClass();

function _enc_obj_if_valued(\stdClass &$out, string $field, \JsonSerializable $obj): void
{
    global $_zeroObject;
    $val = $obj->jsonSerialize();
    if ($val != $_zeroObject) {
        $out->{$field} = $val;
    }
}
