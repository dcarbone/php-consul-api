<?php

declare(strict_types=1);

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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

namespace DCarbone\PHPConsulAPITests;

use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Null_;

/**
 * Returns 3 possible states:
 *
 * 1. parameter is either nullable or singular concrete type
 *  - must typehint concrete [true, $hint]
 * 2. parameter is either nullable or multiple possible concrete types
 *  - must typehint nothing [true, null]
 * 3. parameter is one of multiple concrete types
 *  - must typehint nothing [false, null]
 *
 * @param \phpDocumentor\Reflection\Types\Compound $compoundHint
 * @return array
 */
function determine_param_hint(Compound $compoundHint): array
{
    $out = [
        false, // is nullable
        null,  // required hint
    ];
    $others = [];
    foreach ($compoundHint as $hint) {
        if (!$out[0] && $hint instanceof Null_) {
            $out[0] = true;
        } else {
            $others[] = $hint;
        }
    }
    if (1 === \count($others)) {
        $out[1] = $others[0];
    }
    return $out;
}
