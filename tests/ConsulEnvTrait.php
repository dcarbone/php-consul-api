<?php

namespace DCarbone\PHPConsulAPITests;

use DCarbone\PHPConsulAPI\Consul;

/**
 * Snapshots, clears, and restores every Consul environment variable so config tests are hermetic.
 */
trait ConsulEnvTrait
{
    /** @var array<string,string|false> */
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
            $this->savedConsulEnv[$name] = getenv($name);
            $this->clearEnv($name);
        }
    }

    private function restoreConsulEnv(): void
    {
        foreach ($this->savedConsulEnv as $name => $value) {
            if (false === $value) {
                $this->clearEnv($name);
            } else {
                $this->setEnv(name: $name, value: $value);
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
