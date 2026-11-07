<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

use DCarbone\Go\Time;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;

class Config
{
    public const DEFAULT_REQUEST_OPTIONS = [
        RequestOptions::HTTP_ERRORS    => false,
        RequestOptions::DECODE_CONTENT => false,
    ];

    public const DEFAULT_ADDRESS = 'localhost:8500';
    public const DEFAULT_SCHEME = 'http';

    /**
     * The address, including port, of your Consul Agent.  May be prefixed with http://, https://, or unix://.
     */
    public string $Address;

    public string $Scheme;

    /**
     * Prefix for URI paths, used when Consul sits behind a reverse proxy.  Populated from Address by Config::merge().
     */
    public string $PathPrefix;

    public string $Datacenter;

    public string $Namespace;

    public string $Partition;

    /**
     * HTTP authentication, if used
     */
    public null|HttpAuth $HttpAuth;

    public null|Time\Duration $WaitTime;

    public string $Token;

    /**
     * File containing the current token to use for this client.
     *
     * If provided, it is read once at startup and never again
     */
    public string $TokenFile;

    /**
     * Name of the server used for SNI.  Retained for parity with the Go client; Guzzle has no SNI override, so this
     * is not applied to requests.
     */
    public string $TLSServerName;

    public string $CAFile;

    /**
     * Path to a directory of CA certificates.  Ignored when CAFile is set.
     */
    public string $CAPath;

    /**
     * Optional path to certificate.  If set, KeyFile must also be set
     */
    public string $CertFile;

    /**
     * Optional path to private key.  If set, CertFile must also be set
     */
    public string $KeyFile;

    public bool $InsecureSkipVerify;

    public ClientInterface $HttpClient;

    public int $JSONEncodeOpts;
    public int $JSONDecodeMaxDepth;
    public int $JSONDecodeOpts;

    public function __construct(
        null|string $Address = null,
        null|bool|string $Scheme = null,
        string $Datacenter = '',
        null|string $Namespace = null,
        null|string|HttpAuth $HttpAuth = null,
        null|string|int|float|\DateInterval|Time\Duration $WaitTime = null,
        null|string $Token = null,
        null|string $TokenFile = null,
        null|string $CAFile = null,
        null|string $CertFile = null,
        null|string $KeyFile = null,
        null|bool $InsecureSkipVerify = null,
        null|ClientInterface $HttpClient = null,
        int $JSONEncodeOpts = JSON_UNESCAPED_SLASHES,
        int $JSONDecodeMaxDepth = 512,
        int $JSONDecodeOpts = 0,
        null|string $Partition = null,
        string $PathPrefix = '',
        null|string $CAPath = null,
        null|string $TLSServerName = null,
    ) {
        $this->Address = self::_resolveValue($Address, Consul::HTTPAddrEnvName, self::DEFAULT_ADDRESS);
        if (null !== $Scheme) {
            $this->setScheme($Scheme);
        } else {
            // Unparseable CONSUL_HTTP_SSL values are treated as false, as in the Go client.
            $ssl = self::_envValue(Consul::HTTPSSLEnvName);
            $this->Scheme = (null !== $ssl && true === self::_parseBool($ssl)) ? 'https' : self::DEFAULT_SCHEME;
        }
        $this->PathPrefix = $PathPrefix;
        $this->Datacenter = $Datacenter;
        $this->Namespace = self::_resolveValue($Namespace, Consul::HTTPNamespaceEnvName, '');
        $this->Partition = self::_resolveValue($Partition, Consul::HTTPPartitionEnvName, '');
        $this->setHttpAuth(self::_resolveValue($HttpAuth, Consul::HTTPAuthEnvName, null));
        $this->setWaitTime($WaitTime);
        $this->Token = self::_resolveValue($Token, Consul::HTTPTokenEnvName, '');
        $this->TokenFile = self::_resolveValue($TokenFile, Consul::HTTPTokenFileEnvName, '');
        $this->TLSServerName = self::_resolveValue($TLSServerName, Consul::HTTPTLSServerNameEnvName, '');
        $this->CAFile = self::_resolveValue($CAFile, Consul::HTTPCAFileEnvName, '');
        $this->CAPath = self::_resolveValue($CAPath, Consul::HTTPCAPathEnvName, '');
        $this->CertFile = self::_resolveValue($CertFile, Consul::HTTPClientCertEnvName, '');
        $this->KeyFile = self::_resolveValue($KeyFile, Consul::HTTPClientKeyEnvName, '');
        if (null !== $InsecureSkipVerify) {
            $this->InsecureSkipVerify = $InsecureSkipVerify;
        } else {
            // CONSUL_HTTP_SSL_VERIFY expresses whether to verify.  As in the Go client, any value that does not
            // parse as true disables verification.
            $sslVerify = self::_envValue(Consul::HTTPSSLVerifyEnvName);
            $this->InsecureSkipVerify = null !== $sslVerify && true !== self::_parseBool($sslVerify);
        }

        $this->_validateCertAndKey();

        $this->HttpClient = $HttpClient ?? new Client();

        $this->JSONEncodeOpts = $JSONEncodeOpts;
        $this->JSONDecodeMaxDepth = $JSONDecodeMaxDepth;
        $this->JSONDecodeOpts = $JSONDecodeOpts;
    }

    public function __clone()
    {
        if (null !== $this->HttpAuth) {
            $this->HttpAuth = clone $this->HttpAuth;
        }
    }

    /**
     * Produces the config used by a client, mirroring the Go client's NewClient().  Empty values on the incoming
     * config are populated from the environment-derived defaults, the Address is parsed for a scheme, unix socket,
     * or path prefix, and the token is resolved from TokenFile / Token per the Go client's precedence rules.
     *
     * The incoming config is not modified.
     */
    public static function merge(null|Config $inc): Config
    {
        $def = static::newDefaultConfig();
        $config = null === $inc ? clone $def : clone $inc;

        if ('' === $config->Address) {
            $config->Address = $def->Address;
        }
        if ('' === $config->Scheme) {
            $config->Scheme = $def->Scheme;
        }
        if ('' === $config->TLSServerName) {
            $config->TLSServerName = $def->TLSServerName;
        }
        if ('' === $config->CAFile) {
            $config->CAFile = $def->CAFile;
        }
        if ('' === $config->CAPath) {
            $config->CAPath = $def->CAPath;
        }
        if ('' === $config->CertFile) {
            $config->CertFile = $def->CertFile;
        }
        if ('' === $config->KeyFile) {
            $config->KeyFile = $def->KeyFile;
        }
        if (!$config->InsecureSkipVerify) {
            $config->InsecureSkipVerify = $def->InsecureSkipVerify;
        }
        if ('' === $config->Namespace) {
            $config->Namespace = $def->Namespace;
        }
        if ('' === $config->Partition) {
            $config->Partition = $def->Partition;
        }
        if (0 === $config->JSONEncodeOpts) {
            $config->JSONEncodeOpts = $def->JSONEncodeOpts;
        }
        if (0 === $config->JSONDecodeMaxDepth) {
            $config->JSONDecodeMaxDepth = $def->JSONDecodeMaxDepth;
        }

        $config->_validateCertAndKey();

        $parts = explode('://', $config->Address, 2);
        if (2 === count($parts)) {
            switch ($parts[0]) {
                case 'http':
                    // Never revert to http if TLS was explicitly requested.
                    break;
                case 'https':
                    $config->Scheme = 'https';
                    break;
                case 'unix':
                    $config->HttpClient = new Client(['curl' => [\CURLOPT_UNIX_SOCKET_PATH => $parts[1]]]);
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Unknown protocol scheme: %s', $parts[0]));
            }
            $config->Address = $parts[1];

            if ('http' === $parts[0] || 'https' === $parts[0]) {
                $hostParts = explode('/', $parts[1], 2);
                if (2 === count($hostParts)) {
                    $config->Address = $hostParts[0];
                    $config->PathPrefix = '/' . $hostParts[1];
                }
            }
        }

        // Token precedence, as in the Go client:
        // 1. explicit TokenFile
        // 2. explicit Token
        // 3. CONSUL_HTTP_TOKEN_FILE environment variable
        // 4. CONSUL_HTTP_TOKEN environment variable
        if ('' !== $config->TokenFile && $config->TokenFile !== $def->TokenFile) {
            $token = self::_readTokenFile($config->TokenFile);
            if ('' !== $token) {
                $config->Token = $token;
            }
        } elseif ('' !== $config->Token && $config->Token !== $def->Token) {
            // explicit token wins
        } elseif ('' !== $def->TokenFile) {
            $token = self::_readTokenFile($def->TokenFile);
            if ('' !== $token) {
                $config->Token = $token;
                $config->TokenFile = $def->TokenFile;
            }
        } else {
            $config->Token = $def->Token;
        }

        return $config;
    }

    public static function newDefaultConfig(): self
    {
        return new self();
    }

    /**
     * Returns the environment variables that reproduce this config, mirroring the Go client's GenerateEnv().
     *
     * @return string[]
     */
    public function generateEnv(): array
    {
        return [
            sprintf('%s=%s', Consul::HTTPAddrEnvName, $this->Address),
            sprintf('%s=%s', Consul::HTTPTokenEnvName, $this->Token),
            sprintf('%s=%s', Consul::HTTPTokenFileEnvName, $this->TokenFile),
            sprintf('%s=%s', Consul::HTTPSSLEnvName, 'https' === $this->Scheme ? 'true' : 'false'),
            sprintf('%s=%s', Consul::HTTPCAFileEnvName, $this->CAFile),
            sprintf('%s=%s', Consul::HTTPCAPathEnvName, $this->CAPath),
            sprintf('%s=%s', Consul::HTTPClientCertEnvName, $this->CertFile),
            sprintf('%s=%s', Consul::HTTPClientKeyEnvName, $this->KeyFile),
            sprintf('%s=%s', Consul::HTTPTLSServerNameEnvName, $this->TLSServerName),
            sprintf('%s=%s', Consul::HTTPSSLVerifyEnvName, $this->InsecureSkipVerify ? 'false' : 'true'),
            null !== $this->HttpAuth
                ? sprintf('%s=%s:%s', Consul::HTTPAuthEnvName, $this->HttpAuth->username, $this->HttpAuth->password)
                : sprintf('%s=', Consul::HTTPAuthEnvName),
        ];
    }

    public function getAddress(): string
    {
        return $this->Address;
    }

    public function setAddress(string $address): self
    {
        $this->Address = $address;
        return $this;
    }

    public function getScheme(): string
    {
        return $this->Scheme;
    }

    public function setScheme(bool|string $scheme): self
    {
        $this->Scheme = match (is_string($scheme) ? strtolower($scheme) : $scheme) {
            true, 'true', '1' => 'https',
            false, 'false', '0' => 'http',
            default => $scheme,
        };
        return $this;
    }

    public function getPathPrefix(): string
    {
        return $this->PathPrefix;
    }

    public function setPathPrefix(string $PathPrefix): self
    {
        $this->PathPrefix = $PathPrefix;
        return $this;
    }

    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    public function setDatacenter(string $datacenter): self
    {
        $this->Datacenter = $datacenter;
        return $this;
    }

    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->Namespace = $namespace;
        return $this;
    }

    public function getPartition(): string
    {
        return $this->Partition;
    }

    public function setPartition(string $Partition): self
    {
        $this->Partition = $Partition;
        return $this;
    }

    public function getWaitTime(): null|Time\Duration
    {
        return $this->WaitTime;
    }

    public function setWaitTime(null|string|int|float|\DateInterval|Time\Duration $waitTime): self
    {
        $this->WaitTime = Time::Duration($waitTime);
        return $this;
    }

    public function getToken(): string
    {
        return $this->Token;
    }

    public function setToken(string $token): self
    {
        $this->Token = $token;
        return $this;
    }

    public function getTokenFile(): string
    {
        return $this->TokenFile;
    }

    public function setTokenFile(string $tokenFile): self
    {
        $this->TokenFile = $tokenFile;
        return $this;
    }

    public function isInsecureSkipVerify(): bool
    {
        return $this->InsecureSkipVerify;
    }

    public function setInsecureSkipVerify(bool $insecureSkipVerify): self
    {
        $this->InsecureSkipVerify = $insecureSkipVerify;
        return $this;
    }

    public function getHttpAuth(): null|HttpAuth
    {
        return $this->HttpAuth;
    }

    public function setHttpAuth(null|string|HttpAuth $httpAuth): self
    {
        if (null === $httpAuth) {
            $this->HttpAuth = null;
            return $this;
        }

        if (is_string($httpAuth)) {
            $colon = strpos($httpAuth, ':');
            if (false === $colon) {
                $username = $httpAuth;
                $password = '';
            } else {
                $username = substr($httpAuth, 0, $colon);
                $password = substr($httpAuth, $colon + 1);
            }
            $httpAuth = new HttpAuth($username, $password);
        }

        $this->HttpAuth = $httpAuth;
        return $this;
    }

    public function getTLSServerName(): string
    {
        return $this->TLSServerName;
    }

    public function setTLSServerName(string $TLSServerName): self
    {
        $this->TLSServerName = $TLSServerName;
        return $this;
    }

    public function getCAFile(): string
    {
        return $this->CAFile;
    }

    public function setCAFile(string $caFile): self
    {
        $this->CAFile = $caFile;
        return $this;
    }

    public function getCAPath(): string
    {
        return $this->CAPath;
    }

    public function setCAPath(string $CAPath): self
    {
        $this->CAPath = $CAPath;
        return $this;
    }

    public function getCertFile(): string
    {
        return $this->CertFile;
    }

    public function setCertFile(string $certFile): self
    {
        $this->CertFile = $certFile;
        return $this;
    }

    public function getKeyFile(): string
    {
        return $this->KeyFile;
    }

    public function setKeyFile(string $keyFile): self
    {
        $this->KeyFile = $keyFile;
        return $this;
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->HttpClient;
    }

    public function setHttpClient(ClientInterface $httpClient): self
    {
        $this->HttpClient = $httpClient;
        return $this;
    }

    public function getJSONEncodeOpts(): int
    {
        return $this->JSONEncodeOpts;
    }

    public function setJSONEncodeOpts(int $jsonEncodeOpts): self
    {
        $this->JSONEncodeOpts = $jsonEncodeOpts;
        return $this;
    }

    public function getJSONDecodeMaxDepth(): int
    {
        return $this->JSONDecodeMaxDepth;
    }

    public function setJSONDecodeMaxDepth(int $JSONDecodeMaxDepth): self
    {
        $this->JSONDecodeMaxDepth = $JSONDecodeMaxDepth;
        return $this;
    }

    public function getJSONDecodeOpts(): int
    {
        return $this->JSONDecodeOpts;
    }

    public function setJSONDecodeOpts(int $JSONDecodeOpts): self
    {
        $this->JSONDecodeOpts = $JSONDecodeOpts;
        return $this;
    }

    protected function _validateCertAndKey(): void
    {
        $c = $this->CertFile;
        $k = $this->KeyFile;
        if (('' !== $k && '' === $c) || ('' !== $c && '' === $k)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s - CertFile and KeyFile must be both either empty or populated.  Key: %s; Cert: %s',
                    static::class,
                    $k,
                    $c
                )
            );
        }
    }

    protected static function _readTokenFile(string $file): string
    {
        $data = is_file($file) && is_readable($file) ? file_get_contents($file) : false;
        if (false === $data) {
            throw new \RuntimeException(sprintf('Error loading token file %s : file does not exist or is not readable', $file));
        }
        return trim($data);
    }

    protected static function _resolveValue(mixed $explicit, string $env, mixed $default): mixed
    {
        if (null !== $explicit) {
            return $explicit;
        }
        return self::_envValue($env) ?? $default;
    }

    /**
     * Returns the value of an environment variable, or null if it is unset or empty.
     */
    protected static function _envValue(string $env): null|string
    {
        if (isset($_ENV[$env]) && is_string($_ENV[$env]) && '' !== $_ENV[$env]) {
            return $_ENV[$env];
        }
        if (false !== ($value = getenv($env)) && '' !== $value) {
            return $value;
        }
        if (isset($_SERVER[$env]) && is_string($_SERVER[$env]) && '' !== $_SERVER[$env]) {
            return $_SERVER[$env];
        }
        return null;
    }

    /**
     * Mirrors Go's strconv.ParseBool.  Returns null for values Go would reject.
     */
    protected static function _parseBool(string $value): null|bool
    {
        return match ($value) {
            '1', 't', 'T', 'TRUE', 'true', 'True' => true,
            '0', 'f', 'F', 'FALSE', 'false', 'False' => false,
            default => null,
        };
    }
}
