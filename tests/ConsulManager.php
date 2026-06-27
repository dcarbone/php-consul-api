<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests;

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

/**
 * Class ConsulManager
 */
final class ConsulManager
{
    public const TMP_DIR  = __DIR__ . '/../tmp';
    public const DATA_DIR = self::TMP_DIR . '/consul-data';

    public const START_SINGLE_CMD = __DIR__ . '/run_consul.sh';
    public const STOP_SINGLE_CMD  = __DIR__ . '/stop_consul.sh';

    public const PID_FILE = self::TMP_DIR . '/consul.pid';
    public const LOG_FILE = self::TMP_DIR . '/consul.log';

    public const CONFIG_FILE = __DIR__ . '/consul.hcl';
    public const MANAGEMENT_TOKEN = '00000000-0000-0000-0000-000000000001';
    public const TEST_ADDRESS = '127.0.0.1:8500';
    private const STARTUP_TIMEOUT_SECONDS = 20;
    private const SHUTDOWN_TIMEOUT_SECONDS = 10;

    /**
     * Start up single instance of Consul Agent with specified flags
     *
     * @param string $flags
     */
    public static function startSingle(string $flags): void
    {
        self::ensureTmpDir();

        if (file_exists(self::PID_FILE)) {
            self::stopSingle();
        }

        shell_exec(self::START_SINGLE_CMD . " {$flags}");
        self::waitForReady();
    }

    /**
     * Start a single instance of a consul agent in "-dev" mode
     */
    public static function startSingleDev(): void
    {
        self::startSingle('-dev');
    }

    /**
     * Start a single instance of a consul agent in "-dev" mode with ACL config
     */
    public static function startSingleDevACL(): void
    {
        self::startSingle('-dev -config-file=' . self::CONFIG_FILE);
    }

    /**
     * Stop running instance
     */
    public static function stopSingle(): void
    {
        if (!file_exists(self::PID_FILE)) {
            return;
        }

        $pid = trim((string)file_get_contents(self::PID_FILE));
        shell_exec(self::STOP_SINGLE_CMD);
        self::waitForPidToExit($pid);
        if (file_exists(self::PID_FILE)) {
            unlink(self::PID_FILE);
        }
    }

    /**
     * @param string $Token
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public static function testConfig(string $Token = ''): Config
    {
        return new Config(
            Address: self::TEST_ADDRESS,
            Scheme: 'http',
            Token:  $Token,
        );
    }

    private static function ensureTmpDir(): void
    {
        if (!is_dir(self::TMP_DIR)) {
            mkdir(self::TMP_DIR, 0775, true);
        }
    }

    private static function waitForReady(): void
    {
        $deadline = microtime(true) + self::STARTUP_TIMEOUT_SECONDS;
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 1,
            ],
        ]);

        while (microtime(true) < $deadline) {
            $leader = @file_get_contents(
                sprintf('http://%s/v1/status/leader', self::TEST_ADDRESS),
                false,
                $context
            );
            $peers = @file_get_contents(
                sprintf('http://%s/v1/status/peers', self::TEST_ADDRESS),
                false,
                $context
            );
            $autopilotHealth = @file_get_contents(
                sprintf('http://%s/v1/operator/autopilot/health', self::TEST_ADDRESS),
                false,
                $context
            );

            $leaderReady = is_string($leader) && '' !== trim($leader, "\" \n\r\t");
            $decodedPeers = is_string($peers) ? json_decode($peers, true) : null;
            $peersReady = is_array($decodedPeers) && 1 <= count($decodedPeers);
            $autopilotReady = is_string($autopilotHealth) && '' !== $autopilotHealth;

            if ($leaderReady && $peersReady && $autopilotReady) {
                return;
            }
            usleep(200000);
        }

        throw new \RuntimeException(
            sprintf(
                'Timed out waiting for Consul startup; see %s for details.',
                self::LOG_FILE
            )
        );
    }

    private static function waitForPidToExit(string $pid): void
    {
        if ('' === $pid || !ctype_digit($pid)) {
            return;
        }

        $deadline = microtime(true) + self::SHUTDOWN_TIMEOUT_SECONDS;
        while (microtime(true) < $deadline) {
            if (!self::isPidRunning($pid)) {
                return;
            }
            usleep(200000);
        }
    }

    private static function isPidRunning(string $pid): bool
    {
        return file_exists(sprintf('/proc/%s', $pid));
    }
}
