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

class ServiceResolverConfigEntry extends AbstractModel implements ConfigEntry
{
    use ConfigEntryTrait;

    private const FIELD_DEFAULT_SUBSET  = 'DefaultSubset';
    private const FIELD_SUBSETS         = 'Subsets';
    private const FIELD_REDIRECT        = 'Redirect';
    private const FIELD_FAILOVER        = 'Failover';
    private const FIELD_CONNECT_TIMEOUT = 'ConnectTimeout';
    private const FIELD_LOAD_BALANCER   = 'LoadBalancer';
}
