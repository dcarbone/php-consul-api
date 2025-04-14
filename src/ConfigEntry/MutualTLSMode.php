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

enum MutualTLSMode: string
{
    /**
     * Default represents no specific mode and should
     * be used to indicate that a different layer of the configuration
     * chain should take precedence.
     */
    case Default = '';
    /**
     * Strict requires mTLS for incoming traffic.
     */
    case Strict = 'strict';
    /**
     * Permissive allows incoming non-mTLS traffic.
     */
    case Permissive = 'permissive';
}
