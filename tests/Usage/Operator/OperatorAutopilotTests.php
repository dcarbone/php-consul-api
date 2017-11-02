<?php namespace DCarbone\PHPConsulAPITests\Usage\Operator;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * Class OperatorAutopilotTests
 * @package DCarbone\PHPConsulAPITests\Usage\Operator
 */
class OperatorAutopilotTests extends AbstractUsageTests {
    /** @var bool */
    protected static $singlePerClass = true;

    public function testCanGetAutopilotConfiguration() {
        $client = new OperatorClient(new Config());

        list($conf, $err) = $client->autopilotGetConfiguration();
        $this->assertNull($err, sprintf('Unable to list autopilot configuration: %s', $err));
        $this->assertInstanceOf(AutopilotConfiguration::class,
            $conf,
            sprintf('Expected instance of %s, saw: %s', AutopilotConfiguration::class, json_encode($conf)));
    }

    /**
     * @depends testCanGetAutopilotConfiguration
     */
    public function testCanSetAutopilotConfiguration() {
        $client = new OperatorClient(new Config());

        /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $current */
        /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $updated */
        list($current) = $client->autopilotGetConfiguration();
        $new = clone $current;
        $new->CleanupDeadServers = !$current->CleanupDeadServers;
        $err = $client->autopilotSetConfiguration($new);
        $this->assertNull($err, 'Unable to update Autopilot configuration: '.$err);
        list($updated, $err) = $client->autopilotGetConfiguration();
        $this->assertNull($err, 'Unable to get updated Autopilot configuration: '.$err);
        $this->assertInstanceOf(AutopilotConfiguration::class, $updated);
        if ($current->CleanupDeadServers) {
            $this->assertFalse($updated->CleanupDeadServers, 'Autopilot conf did not change');
        } else {
            $this->assertTrue($updated->CleanupDeadServers, 'Autopilot conf did not change');
        }
    }

    /**
     * @depends testCanSetAutopilotConfiguration
     */
    public function testCanCASAutopilotConfiguration() {
        $client = new OperatorClient(new Config());
        /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $current */
        /** @var \DCarbone\PHPConsulAPI\Operator\AutopilotConfiguration $updated */
        list($current) = $client->autopilotGetConfiguration();
        $new = clone $current;
        $new->CleanupDeadServers = !$current->CleanupDeadServers;
        list($ok, $err) = $client->autopilotCASConfiguration($new);
        $this->assertNull($err, 'Unable to update Autopilot configuration: '.$err);
        $this->assertTrue($ok);
        list($updated, $err) = $client->autopilotGetConfiguration();
        $this->assertNull($err, 'Unable to get updated Autopilot configuration: '.$err);
        $this->assertInstanceOf(AutopilotConfiguration::class, $updated);
        if ($current->CleanupDeadServers) {
            $this->assertFalse($updated->CleanupDeadServers, 'Autopilot conf did not change');
        } else {
            $this->assertTrue($updated->CleanupDeadServers, 'Autopilot conf did not change');
        }
    }

    public function testCanGetAutopilotServerHealth() {
        $client = new OperatorClient(new Config());

        /** @var \DCarbone\PHPConsulAPI\Operator\OperatorHealthReply $healths */
        list($healths, $err) = $client->autopilotServerHealth();
        $this->assertNull($err, 'Unable to get Autopilot server health: %s'.$err);
        $this->assertInstanceOf(OperatorHealthReply::class, $healths);
        $this->assertCount(1, $healths->Servers);
    }
}