<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPITests;

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

use DCarbone\PHPConsulAPI\Config;

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

    public const TEST_HTTPS_PORT       = 8501;
    public const TEST_HTTPS_ADDRESS    = '127.0.0.1:8501';
    public const UNIX_SOCKET           = self::TMP_DIR . '/consul.sock';
    public const TLS_DIR               = self::TMP_DIR . '/tls';
    public const TLS_CA_PATH_DIR       = self::TLS_DIR . '/capath';
    public const TLS_CONFIG_FILE       = self::TMP_DIR . '/consul-tls.hcl';
    public const TLS_CA_FILE           = self::TLS_DIR . '/consul-agent-ca.pem';
    public const TLS_SERVER_CERT_FILE  = self::TLS_DIR . '/dc1-server-consul-0.pem';
    public const TLS_SERVER_KEY_FILE   = self::TLS_DIR . '/dc1-server-consul-0-key.pem';
    public const TLS_CLIENT_CERT_FILE  = self::TLS_DIR . '/dc1-client-consul-0.pem';
    public const TLS_CLIENT_KEY_FILE   = self::TLS_DIR . '/dc1-client-consul-0-key.pem';
    private const STARTUP_TIMEOUT_SECONDS = 20;
    private const SHUTDOWN_TIMEOUT_SECONDS = 10;

    /**
     * Start up single instance of Consul Agent with specified flags
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
     * Start a single "-dev" agent with ACLs plus a unix socket listener and an HTTPS listener that requires a client
     * certificate.  TLS assets are generated into {@see self::TLS_DIR} using the consul binary.
     */
    public static function startSingleDevTLS(): void
    {
        self::ensureTmpDir();
        self::generateTLSAssets();

        if (file_exists(self::UNIX_SOCKET)) {
            unlink(self::UNIX_SOCKET);
        }

        $hcl = sprintf(
            <<<HCL
            addresses {
              http = "127.0.0.1 unix://%s"
            }
            ports {
              https = %d
            }
            tls {
              https {
                ca_file         = "%s"
                cert_file       = "%s"
                key_file        = "%s"
                verify_incoming = true
              }
            }
            HCL,
            self::UNIX_SOCKET,
            self::TEST_HTTPS_PORT,
            self::TLS_CA_FILE,
            self::TLS_SERVER_CERT_FILE,
            self::TLS_SERVER_KEY_FILE,
        );

        file_put_contents(self::TLS_CONFIG_FILE, $hcl);

        self::startSingle(sprintf('-dev -config-file=%s -config-file=%s', self::CONFIG_FILE, self::TLS_CONFIG_FILE));
        self::waitForFile(self::UNIX_SOCKET);
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

    /**
     * Returns true if a hashed CA directory (for CURLOPT_CAPATH) could be created.
     */
    public static function hasCAPathDir(): bool
    {
        return is_dir(self::TLS_CA_PATH_DIR) && [] !== glob(self::TLS_CA_PATH_DIR . '/*.0');
    }

    private static function generateTLSAssets(): void
    {
        if (is_dir(self::TLS_DIR)) {
            self::removeDir(self::TLS_DIR);
        }
        mkdir(self::TLS_DIR, 0775, true);

        $cmds = [
            'consul tls ca create',
            'consul tls cert create -server -dc dc1',
            'consul tls cert create -client -dc dc1',
        ];
        foreach ($cmds as $cmd) {
            $out  = [];
            $code = 0;
            exec(sprintf('cd %s && %s 2>&1', escapeshellarg(self::TLS_DIR), $cmd), $out, $code);
            if (0 !== $code) {
                throw new \RuntimeException(sprintf('"%s" failed: %s', $cmd, implode("\n", $out)));
            }
        }

        // CURLOPT_CAPATH requires OpenSSL hash-named files; best effort, tests skip if unavailable.
        mkdir(self::TLS_CA_PATH_DIR, 0775, true);
        copy(self::TLS_CA_FILE, self::TLS_CA_PATH_DIR . '/consul-agent-ca.pem');
        $hash = trim((string)shell_exec(sprintf('openssl x509 -hash -noout -in %s 2>/dev/null', escapeshellarg(self::TLS_CA_FILE))));
        if ('' !== $hash) {
            copy(self::TLS_CA_FILE, sprintf('%s/%s.0', self::TLS_CA_PATH_DIR, $hash));
        }
    }

    private static function removeDir(string $dir): void
    {
        $items = scandir($dir);
        if (false === $items) {
            return;
        }
        foreach ($items as $item) {
            if ('.' === $item || '..' === $item) {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                self::removeDir($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    private static function waitForFile(string $file): void
    {
        $deadline = microtime(true) + self::STARTUP_TIMEOUT_SECONDS;
        while (microtime(true) < $deadline) {
            clearstatcache(true, $file);
            if (file_exists($file)) {
                return;
            }
            usleep(100000);
        }

        throw new \RuntimeException(sprintf('Timed out waiting for "%s"; see %s for details.', $file, self::LOG_FILE));
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
