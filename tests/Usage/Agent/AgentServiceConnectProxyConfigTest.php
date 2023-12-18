<?php

namespace DCarbone\PHPConsulAPITests\Usage\Agent;

use DCarbone\PHPConsulAPI\Agent\AgentServiceConnectProxyConfig;
use DCarbone\PHPConsulAPITests\Usage\AbstractUsageTests;

/**
 * @copyright ResearchGate GmbH
 */
class AgentServiceConnectProxyConfigTest extends AbstractUsageTests
{
    public function test_construct_givenConfig_willUnmarshalConfigValuesSuccessfully()
    {
        $config = new AgentServiceConnectProxyConfig([
            'Config' => [
                'envoy_prometheus_bind_addr' => "0.0.0.0:12345",
                'handshake_timeout_ms' => 10000,
                'local_connect_timeout_ms' => 1000,
                'local_request_timeout_ms' => 0,
                'protocol' => "http",
            ],
        ]);

        $this->assertSame([
            'envoy_prometheus_bind_addr' => "0.0.0.0:12345",
            'handshake_timeout_ms' => 10000,
            'local_connect_timeout_ms' => 1000,
            'local_request_timeout_ms' => 0,
            'protocol' => "http",
        ], $config->Config);
    }
}
