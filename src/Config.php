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
    use Unmarshaller;

    public const DEFAULT_REQUEST_OPTIONS = [
        RequestOptions::HTTP_ERRORS    => false,
        RequestOptions::DECODE_CONTENT => false,
    ];

    protected const FIELDS = [
        self::FIELD_HTTP_AUTH => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => 'setHttpAuth',
        ],
        self::FIELD_WAIT_TIME => [
            Transcoding::FIELD_UNMARSHAL_CALLBACK => Transcoding::UNMARSHAL_NULLABLE_DURATION,
        ],
    ];

    private const FIELD_HTTP_AUTH            = 'HttpAuth';
    private const FIELD_WAIT_TIME            = 'WaitTime';
    private const FIELD_ADDRESS              = 'Address';
    private const FIELD_SCHEME               = 'Scheme';
    private const FIELD_JSON_ENCODE_OPTS     = 'JSONEncodeOpts';
    private const FIELD_TOKEN                = 'Token';
    private const FIELD_TOKEN_FILE           = 'TokenFile';
    private const FIELD_CA_FILE              = 'CAFile';
    private const FIELD_CERT_FILE            = 'CertFile';
    private const FIELD_KEY_FILE             = 'KeyFile';
    private const FIELD_INSECURE_SKIP_VERIFY = 'InsecureSkipVerify';

    private const DEFAULT_CONFIG = [
        self::FIELD_ADDRESS          => '127.0.0.1:8500',
        self::FIELD_SCHEME           => 'http',
        self::FIELD_JSON_ENCODE_OPTS => \JSON_UNESCAPED_SLASHES,
    ];

    /**
     * The address, including port, of your Consul Agent
     *
     * @var string
     */
    public string $Address = '';

    public string $Scheme = '';

    public string $Datacenter = '';

    public string $Namespace = '';

    /**
     * HTTP authentication, if used
     *
     * @var \DCarbone\PHPConsulAPI\HttpAuth|null
     */
    public ?HttpAuth $HttpAuth = null;

    public ?Time\Duration $WaitTime = null;

    public string $Token = '';

    /**
     * File containing the current token to use for this client.
     *
     * If provided, it is read once at startup and never again
     *
     * @var string
     */
    public string $TokenFile = '';

    public string $CAFile = '';

    /**
     * Optional path to certificate.  If set, KeyFile must also be set
     *
     * @var string
     */
    public string $CertFile = '';

    /**
     * Optional path to private key.  If set, CertFile must also be set
     *
     * @var string
     */
    public string $KeyFile = '';

    public bool $InsecureSkipVerify = false;

    public ClientInterface $HttpClient;

    public int $JSONEncodeOpts = 0;

    public function __construct(array $config = [])
    {
        foreach ($config + self::_getDefaultConfig() as $k => $v) {
            $this->unmarshalField($k, $v);
        }

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

        // if client hasn't been constructed, construct.
        if (!isset($this->HttpClient)) {
            $this->HttpClient = new Client();
        }
    }

    public static function merge(?self $inc): self
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
        return $actual;
    }

    public static function newDefaultConfig(): self
    {
        return new static(self::_getDefaultConfig());
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

    public function setScheme(string $scheme): self
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

    public function setWaitTime(mixed $waitTime): self
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

    public function setHttpAuth(HttpAuth|string $httpAuth): self
    {
        if (\is_string($httpAuth)) {
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

        if ($httpAuth instanceof HttpAuth) {
            $this->HttpAuth = $httpAuth;
            return $this;
        }

        throw new \InvalidArgumentException(
            sprintf(
                '%s::setHttpAuth - Value is expected to be string of "username:password" or instance of "\\DCarbone\\PHPConsulApi\\HttpAuth", %s seen.',
                static::class,
                \is_string($httpAuth) ? $httpAuth : \gettype($httpAuth)
            )
        );
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

    public static function getEnvironmentConfig(): array
    {
        $ret = [];
        foreach (
            [
                Consul::HTTPAddrEnvName       => static::_tryGetEnvParam(Consul::HTTPAddrEnvName),
                Consul::HTTPTokenEnvName      => static::_tryGetEnvParam(Consul::HTTPTokenEnvName),
                Consul::HTTPTokenFileEnvName  => static::_tryGetEnvParam(Consul::HTTPTokenFileEnvName),
                Consul::HTTPAuthEnvName       => static::_tryGetEnvParam(Consul::HTTPAuthEnvName),
                Consul::HTTPCAFileEnvName     => static::_tryGetEnvParam(Consul::HTTPCAFileEnvName),
                Consul::HTTPClientCertEnvName => static::_tryGetEnvParam(Consul::HTTPClientCertEnvName),
                Consul::HTTPClientKeyEnvName  => static::_tryGetEnvParam(Consul::HTTPClientKeyEnvName),
                Consul::HTTPSSLEnvName        => static::_tryGetEnvParam(Consul::HTTPSSLEnvName),
                Consul::HTTPSSLVerifyEnvName  => static::_tryGetEnvParam(Consul::HTTPSSLVerifyEnvName),
            ] as $k => $v
        ) {
            if (null !== $v) {
                $ret[$k] = $v;
            }
        }
        return $ret;
    }

    protected static function _tryGetEnvParam(string $param): ?string
    {
        if (isset($_ENV[$param])) {
            return $_ENV[$param];
        }

        if (false !== ($value = getenv($param))) {
            return $value;
        }

        if (isset($_SERVER[$param])) {
            return $_SERVER[$param];
        }

        return null;
    }

    private static function _getDefaultConfig(): array
    {
        $conf = self::DEFAULT_CONFIG;

        // parse env vars
        foreach (static::getEnvironmentConfig() as $k => $v) {
            if (Consul::HTTPAddrEnvName === $k) {
                $conf[self::FIELD_ADDRESS] = $v;
            } elseif (Consul::HTTPTokenEnvName === $k) {
                $conf[self::FIELD_TOKEN] = $v;
            } elseif (Consul::HTTPTokenFileEnvName === $k) {
                $conf[self::FIELD_TOKEN_FILE] = $v;
            } elseif (Consul::HTTPAuthEnvName === $k) {
                $conf[self::FIELD_HTTP_AUTH] = $v;
            } elseif (Consul::HTTPCAFileEnvName === $k) {
                $conf[self::FIELD_CA_FILE] = $v;
            } elseif (Consul::HTTPClientCertEnvName === $k) {
                $conf[self::FIELD_CERT_FILE] = $v;
            } elseif (Consul::HTTPClientKeyEnvName === $k) {
                $conf[self::FIELD_KEY_FILE] = $v;
            } elseif (Consul::HTTPSSLEnvName === $k) {
                if ((bool)$v) {
                    $conf[self::FIELD_SCHEME] = 'https';
                }
            } elseif (Consul::HTTPSSLVerifyEnvName === $k) {
                if ((bool)$v) {
                    $conf[self::FIELD_INSECURE_SKIP_VERIFY] = true;
                }
            }
        }

        return $conf;
    }
}
