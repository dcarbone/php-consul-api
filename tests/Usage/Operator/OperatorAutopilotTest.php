<?php namespace DCarbone\PHPConsulAPITests\Usage\Operator;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration;
use DCarbone\PHPConsulAPI\Operator\OperatorClient;
use DCarbone\PHPConsulAPI\Operator\OperatorHealthReply;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * Class OperatorAutopilotTest
 *
 * @internal
 */
final class OperatorAutopilotTest extends AbstractUsageTests
{
    /** @var bool */
    protected static $singlePerClass = true;

    public function testCanGetAutopilotConfiguration(): void
    {
        $client = new OperatorClient(new Config());

        [$conf, $err] = $client->AutopilotGetConfiguration();
        static::assertNull($err, \sprintf('Unable to list autopilot configuration: %s', $err));
        static::assertInstanceOf(
            AutopilotConfiguration::class,
            $conf,
            \sprintf('Expected instance of %s, saw: %s', AutopilotConfiguration::class, \json_encode($conf))
        );
    }

    /**
     * @depends testCanGetAutopilotConfiguration
     */
    public function testCanSetAutopilotConfiguration(): void
    {
        $client = new OperatorClient(new Config());

        /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $current */
        /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $updated */
        [$current]               = $client->AutopilotGetConfiguration();
        $new                     = clone $current;
        $new->CleanupDeadServers = !$current->CleanupDeadServers;
        $err                     = $client->AutopilotSetConfiguration($new);
        static::assertNull($err, 'Unable to update Autopilot configuration: ' . $err);
        [$updated, $err] = $client->AutopilotGetConfiguration();
        static::assertNull($err, 'Unable to get updated Autopilot configuration: ' . $err);
        static::assertInstanceOf(AutopilotConfiguration::class, $updated);
        if ($current->CleanupDeadServers) {
            static::assertFalse($updated->CleanupDeadServers, 'Autopilot conf did not change');
        } else {
            static::assertTrue($updated->CleanupDeadServers, 'Autopilot conf did not change');
        }
    }

    /**
     * @depends testCanSetAutopilotConfiguration
     */
    public function testCanCASAutopilotConfiguration(): void
    {
        $client = new OperatorClient(new Config());
        /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $current */
        /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $updated */
        [$current]               = $client->AutopilotGetConfiguration();
        $new                     = clone $current;
        $new->CleanupDeadServers = !$current->CleanupDeadServers;
        [$ok, $err]              = $client->AutopilotCASConfiguration($new);
        static::assertNull($err, 'Unable to update Autopilot configuration: ' . $err);
        static::assertTrue($ok);
        [$updated, $err] = $client->AutopilotGetConfiguration();
        static::assertNull($err, 'Unable to get updated Autopilot configuration: ' . $err);
        static::assertInstanceOf(AutopilotConfiguration::class, $updated);
        if ($current->CleanupDeadServers) {
            static::assertFalse($updated->CleanupDeadServers, 'Autopilot conf did not change');
        } else {
            static::assertTrue($updated->CleanupDeadServers, 'Autopilot conf did not change');
        }
    }

    public function testCanGetAutopilotServerHealth(): void
    {
        $client = new OperatorClient(new Config());

        /** @var \DCarbone\PHPConsulAPI\Operator\OperatorHealthReply $healths */
        [$healths, $err] = $client->AutopilotServerHealth();
        static::assertNull($err, 'Unable to get Autopilot server health: %s' . $err);
        static::assertInstanceOf(OperatorHealthReply::class, $healths);
        static::assertCount(1, $healths->Servers);
    }
}
