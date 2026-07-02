<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests\Integration\Agent;

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

use DCarbone\PHPConsulAPI\Agent\AgentClient;
use DCarbone\PHPConsulAPITests\ConsulManager;
use DCarbone\PHPConsulAPITests\Integration\AbstractUsageTests;

/**
 * @internal
 */
final class AgentClientLifecycleTest extends AbstractUsageTests
{
    protected bool $singlePerTest = true;

    public function testCanLeaveAgent(): void
    {
        $client = new AgentClient(ConsulManager::testConfig());
        $err = $client->Leave();
        self::assertNull($err, sprintf('AgentClient::leave returned error: %s', $err));
    }
}
