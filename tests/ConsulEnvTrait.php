<?php

namespace DCarbone\PHPConsulAPITests;

use DCarbone\PHPConsulAPI\Consul;

/**
 * Snapshots, clears, and restores every Consul environment variable so config tests are hermetic.
 */
trait ConsulEnvTrait
{
    /** @var array<string,array{getenv: string|false, env: array{0: bool, 1: mixed}, server: array{0: bool, 1: mixed}}> */
    private array $savedConsulEnv = [];

    /**
     * @return string[]
     */
    private static function consulEnvNames(): array
    {
        return [
            Consul::HTTPAddrEnvName,
            Consul::HTTPTokenEnvName,
            Consul::HTTPTokenFileEnvName,
            Consul::HTTPAuthEnvName,
            Consul::HTTPCAFileEnvName,
            Consul::HTTPCAPathEnvName,
            Consul::HTTPClientCertEnvName,
            Consul::HTTPClientKeyEnvName,
            Consul::HTTPTLSServerNameEnvName,
            Consul::HTTPSSLEnvName,
            Consul::HTTPSSLVerifyEnvName,
            Consul::HTTPNamespaceEnvName,
            Consul::HTTPPartitionEnvName,
        ];
    }

    private function isolateConsulEnv(): void
    {
        $this->savedConsulEnv = [];
        foreach (self::consulEnvNames() as $name) {
            $this->savedConsulEnv[$name] = [
                'getenv' => getenv($name),
                'env'    => [array_key_exists($name, $_ENV), $_ENV[$name] ?? null],
                'server' => [array_key_exists($name, $_SERVER), $_SERVER[$name] ?? null],
            ];
            $this->clearEnv($name);
        }
    }

    private function restoreConsulEnv(): void
    {
        foreach ($this->savedConsulEnv as $name => $saved) {
            if (false === $saved['getenv']) {
                putenv($name);
            } else {
                putenv(sprintf('%s=%s', $name, $saved['getenv']));
            }

            [$present, $value] = $saved['env'];
            if ($present) {
                $_ENV[$name] = $value;
            } else {
                unset($_ENV[$name]);
            }

            [$present, $value] = $saved['server'];
            if ($present) {
                $_SERVER[$name] = $value;
            } else {
                unset($_SERVER[$name]);
            }
        }
        $this->savedConsulEnv = [];
    }

    private function setEnv(string $name, string $value): void
    {
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }

    private function clearEnv(string $name): void
    {
        putenv($name);
        unset($_ENV[$name], $_SERVER[$name]);
    }
}
