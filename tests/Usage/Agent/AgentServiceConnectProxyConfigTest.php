<?php

namespace DCarbone\PHPConsulAPITests\Usage\Agent;

/*
   Copyright 2023 Daniel Carbone (daniel.p.carbone@gmail.com)
   Copyright 2023 ResearchGate GmbH

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

use DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

class AgentServiceConnectProxyConfigTest extends AbstractUsageTests
{
    public function test_construct_givenConfig_willUnmarshalConfigValuesSuccessfully()
    {
        $config = new AgentServiceConnectProxyConfig(
            Config: (object)[
                'envoy_prometheus_bind_addr' => '0.0.0.0:12345',
                'handshake_timeout_ms' => 10000,
                'local_connect_timeout_ms' => 1000,
                'local_request_timeout_ms' => 0,
                'protocol' => 'http',
            ],
        );

        $this->assertEquals((object)[
            'envoy_prometheus_bind_addr' => '0.0.0.0:12345',
            'handshake_timeout_ms' => 10000,
            'local_connect_timeout_ms' => 1000,
            'local_request_timeout_ms' => 0,
            'protocol' => 'http',
        ], $config->Config);
    }
}