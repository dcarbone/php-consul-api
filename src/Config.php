<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

    public const DEFAULT_ADDRESS = '127.0.0.1:8500';
    public const DEFAULT_SCHEME = 'http';


    /**
     * The address, including port, of your Consul Agent
     *
     * @var string
     */
    public string $Address;

    public string $Scheme;

    public string $Datacenter;

    public string $Namespace;

    /**
     * HTTP authentication, if used
     *
     * @var \DCarbone\PHPConsulAPI\HttpAuth|null
     */
    public null|HttpAuth $HttpAuth;

    public null|Time\Duration $WaitTime;

    public string $Token;

    /**
     * File containing the current token to use for this client.
     *
     * If provided, it is read once at startup and never again
     *
     * @var string
     */
    public string $TokenFile;

    public string $CAFile;

    /**
     * Optional path to certificate.  If set, KeyFile must also be set
     *
     * @var string
     */
    public string $CertFile;

    /**
     * Optional path to private key.  If set, CertFile must also be set
     *
     * @var string
     */
    public string $KeyFile;

    public bool $InsecureSkipVerify;

    public ClientInterface $HttpClient;

    public int $JSONEncodeOpts;
    public int $JSONDecodeMaxDepth;
    public int $JSONDecodeOpts;

    public function __construct(
        string $Address = self::DEFAULT_ADDRESS,
        string $Scheme = self::DEFAULT_SCHEME,
        string $Datacenter = '',
        string $Namespace = '',
        null|string|HttpAuth $HttpAuth = null,
        null|string|int|float|\DateInterval|Time\Duration $WaitTime = null,
        string $Token = '',
        string $TokenFile = '',
        string $CAFile = '',
        string $CertFile = '',
        string $KeyFile = '',
        bool $InsecureSkipVerify = false,
        null|ClientInterface $HttpClient = null,
        int $JSONEncodeOpts = JSON_UNESCAPED_SLASHES,
        int $JSONDecodeMaxDepth = 512,
        int $JSONDecodeOpts = 0,
    ) {
        $this->Address = self::_resolveValue($Address, Consul::HTTPAddrEnvName, self::DEFAULT_ADDRESS);
        $scheme = strtolower(self::_resolveValue($Scheme, Consul::HTTPSSLEnvName, self::DEFAULT_SCHEME));
        $this->Scheme = match ($scheme) {
            'true' => 'https',
            'false' => 'http',
            default => $scheme,
        };
        $this->Datacenter = $Datacenter;
        $this->Namespace = $Namespace;
        $this->setHttpAuth(self::_resolveValue($HttpAuth, Consul::HTTPAuthEnvName, null));
        $this->setWaitTime($WaitTime);
        $this->Token = self::_resolveValue($Token, Consul::HTTPTokenEnvName, '');
        $this->TokenFile = self::_resolveValue($TokenFile, Consul::HTTPTokenFileEnvName, '');
        $this->CAFile = self::_resolveValue($CAFile, Consul::HTTPCAFileEnvName, '');
        $this->CertFile = self::_resolveValue($CertFile, Consul::HTTPClientCertEnvName, '');
        $this->KeyFile = self::_resolveValue($KeyFile, Consul::HTTPClientKeyEnvName, '');
        $skipVerify = self::_resolveValue($InsecureSkipVerify, Consul::HTTPSSLVerifyEnvName, false);
        $this->InsecureSkipVerify = is_string($skipVerify) ? strtolower($skipVerify) === 'true' : $skipVerify;

        // quick validation on key/cert combo
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

        $this->HttpClient = $HttpClient ?? new Client();

        $this->JSONEncodeOpts = $JSONEncodeOpts;
        $this->JSONDecodeMaxDepth = $JSONDecodeMaxDepth;
        $this->JSONDecodeOpts = $JSONDecodeOpts;
    }

    public static function merge(null|Config $inc): Config
    {
        $actual = static::newDefaultConfig();
        if (null === $inc) {
            return $actual;
        }
        if ('' !== $inc->Address) {
            $actual->Address = $inc->Address;
        }
        if ('' !== $inc->Scheme) {
            $actual->Scheme = $inc->Scheme;
        }
        if ('' !== $inc->Datacenter) {
            $actual->Datacenter = $inc->Datacenter;
        }
        if ('' !== $inc->Namespace) {
            $actual->Namespace = $inc->Namespace;
        }
        if (isset($inc->HttpAuth)) {
            $actual->HttpAuth = clone $inc->HttpAuth;
        }
        if (isset($inc->WaitTime)) {
            $actual->WaitTime = Time::Duration($inc->WaitTime);
        }
        if ('' !== $inc->Token) {
            $actual->Token = $inc->Token;
        }
        if ('' !== $inc->TokenFile) {
            $actual->TokenFile = $inc->TokenFile;
        }
        if ('' !== $inc->CAFile) {
            $actual->CAFile = $inc->CAFile;
        }
        if ('' !== $inc->CertFile) {
            $actual->CertFile = $inc->CertFile;
        }
        if ('' !== $inc->KeyFile) {
            $actual->KeyFile = $inc->KeyFile;
        }
        if ($inc->InsecureSkipVerify) {
            $actual->InsecureSkipVerify = true;
        }
        if (null !== $inc->HttpClient) {
            $actual->HttpClient = $inc->HttpClient;
        }
        if (0 !== $inc->JSONEncodeOpts) {
            $actual->JSONEncodeOpts = $inc->JSONEncodeOpts;
        }
        if (0 !== $inc->JSONDecodeMaxDepth) {
            $actual->JSONDecodeMaxDepth = $inc->JSONDecodeMaxDepth;
        }
        $actual->JSONDecodeOpts = self::_resolveValue($inc->JSONDecodeOpts, '', $actual->JSONDecodeOpts);
        return $actual;
    }

    public static function newDefaultConfig(): self
    {
        return new static();
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
        $this->Scheme = $scheme;
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

    public function setNamespace(string $namespace): void
    {
        $this->Namespace = $namespace;
    }

    public function getWaitTime(): Time\Duration
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

    public function getHttpAuth(): HttpAuth
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
                $password = null;
            } else {
                $username = substr($httpAuth, 0, $colon);
                $password = substr($httpAuth, $colon + 1);
            }
            $httpAuth = new HttpAuth($username, $password);
        }

        $this->HttpAuth = $httpAuth;
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

    public function setJSONDecodeMaxDepth(int $JSONDecodeMaxDepth): Config
    {
        $this->JSONDecodeMaxDepth = $JSONDecodeMaxDepth;
        return $this;
    }

    public function getJSONDecodeOpts(): int
    {
        return $this->JSONDecodeOpts;
    }

    public function setJSONDecodeOpts(int $JSONDecodeOpts): Config
    {
        $this->JSONDecodeOpts = $JSONDecodeOpts;
        return $this;
    }

    protected static function _resolveValue(mixed $explicit, string $env, mixed $default): mixed
    {
        if ($explicit !== $default) {
            return $explicit;
        }

        if ($env !== '') {
            if (isset($_ENV[$env])) {
                return $_ENV[$env];
            } elseif (false !== ($value = getenv($env))) {
                return $value;
            } elseif (isset($_SERVER[$env])) {
                return $_SERVER[$env];
            }
        }

        return $default;
    }
}
