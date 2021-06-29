<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

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

use DCarbone\Go\Time;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;

/**
 * Class Config
 */
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

    /**
     * The scheme to use.  Currently only HTTP and HTTPS are supported.
     *
     * @var string
     */
    public string $Scheme = '';

    /**
     * The name of the datacenter you wish all queries to be made against by default
     *
     * @var string
     */
    public string $Datacenter = '';

    /**
     * @var string
     */
    public string $Namespace = '';

    /**
     * HTTP authentication, if used
     *
     * @var \DCarbone\PHPConsulAPI\HttpAuth|null
     */
    public ?HttpAuth $HttpAuth = null;

    /**
     * Time to wait on certain blockable endpoints
     *
     * @var \DCarbone\Go\Time\Duration|null
     */
    public ?Time\Duration $WaitTime = null;

    /**
     * ACL token to use by default
     *
     * @var string
     */
    public string $Token = '';

    /**
     * File containing the current token to use for this client.
     *
     * If provided, it is read once at startup and never again
     *
     * @var string
     */
    public string $TokenFile = '';

    /**
     * Optional path to CA certificate
     *
     * @var string
     */
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

    /**
     * Whether to skip SSL validation.  This does nothing unless you use it within your HttpClient of choice.
     *
     * @var bool
     */
    public bool $InsecureSkipVerify = false;

    /**
     * Your HttpClient of choice.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    public ClientInterface $HttpClient;

    /**
     * Bitwise options to provide to JSON encoder when encoding request bodies
     *
     * @var int
     */
    public int $JSONEncodeOpts = 0;

    /**
     * Config constructor.
     * @param array $config
     */
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
                \sprintf(
                    '%s - CertFile and KeyFile must be both either empty or populated.  Key: %s; Cert: %s',
                    \get_class($this),
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

    /**
     * @param \DCarbone\PHPConsulAPI\Config|null $inc
     * @return \DCarbone\PHPConsulAPI\Config
     */
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

    /**
     * Construct a configuration object from Environment Variables and use bare guzzle client instance
     *
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public static function newDefaultConfig(): self
    {
        return new static(self::_getDefaultConfig());
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->Address;
    }

    /**
     * @param string $address
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setAddress(string $address): self
    {
        $this->Address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->Scheme;
    }

    /**
     * @param string $scheme
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setScheme(string $scheme): self
    {
        $this->Scheme = $scheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter(): string
    {
        return $this->Datacenter;
    }

    /**
     * @param string $datacenter
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setDatacenter(string $datacenter): self
    {
        $this->Datacenter = $datacenter;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->Namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->Namespace = $namespace;
    }

    /**
     * @return \DCarbone\Go\Time\Duration
     */
    public function getWaitTime(): Time\Duration
    {
        return $this->WaitTime;
    }

    /**
     * @param mixed $waitTime
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setWaitTime($waitTime): self
    {
        $this->WaitTime = Time::Duration($waitTime);
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->Token;
    }

    /**
     * @param string $token
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setToken(string $token): self
    {
        $this->Token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenFile(): string
    {
        return $this->TokenFile;
    }

    /**
     * @param string $tokenFile
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setTokenFile(string $tokenFile): self
    {
        $this->TokenFile = $tokenFile;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInsecureSkipVerify(): bool
    {
        return $this->InsecureSkipVerify;
    }

    /**
     * @param bool $insecureSkipVerify
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setInsecureSkipVerify(bool $insecureSkipVerify): self
    {
        $this->InsecureSkipVerify = $insecureSkipVerify;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\HttpAuth
     */
    public function getHttpAuth(): HttpAuth
    {
        return $this->HttpAuth;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\HttpAuth|string $httpAuth
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setHttpAuth($httpAuth): self
    {
        if (\is_string($httpAuth)) {
            $colon = \strpos($httpAuth, ':');
            if (false === $colon) {
                $username = $httpAuth;
                $password = null;
            } else {
                $username = \substr($httpAuth, 0, $colon);
                $password = \substr($httpAuth, $colon + 1);
            }
            $httpAuth = new HttpAuth($username, $password);
        }

        if ($httpAuth instanceof HttpAuth) {
            $this->HttpAuth = $httpAuth;
            return $this;
        }

        throw new \InvalidArgumentException(
            \sprintf(
                '%s::setHttpAuth - Value is expected to be string of "username:password" or instance of "\\DCarbone\\PHPConsulApi\\HttpAuth", %s seen.',
                \get_class($this),
                \is_string($httpAuth) ? $httpAuth : \gettype($httpAuth)
            )
        );
    }

    /**
     * @return string
     */
    public function getCAFile(): string
    {
        return $this->CAFile;
    }

    /**
     * @param string $caFile
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setCAFile(string $caFile): self
    {
        $this->CAFile = $caFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertFile(): string
    {
        return $this->CertFile;
    }

    /**
     * @param string $certFile
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setCertFile(string $certFile): self
    {
        $this->CertFile = $certFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeyFile(): string
    {
        return $this->KeyFile;
    }

    /**
     * @param string $keyFile
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setKeyFile(string $keyFile): self
    {
        $this->KeyFile = $keyFile;
        return $this;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->HttpClient;
    }

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setHttpClient(ClientInterface $httpClient): self
    {
        $this->HttpClient = $httpClient;
        return $this;
    }

    /**
     * @return int
     */
    public function getJSONEncodeOpts(): int
    {
        return $this->JSONEncodeOpts;
    }

    /**
     * @param int $jsonEncodeOpts
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setJSONEncodeOpts(int $jsonEncodeOpts): self
    {
        $this->JSONEncodeOpts = $jsonEncodeOpts;
        return $this;
    }

    /**
     * @return array
     */
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

    /**
     * @param string $param
     * @return string|null
     */
    protected static function _tryGetEnvParam(string $param): ?string
    {
        if (isset($_ENV[$param])) {
            return $_ENV[$param];
        }

        if (false !== ($value = \getenv($param))) {
            return $value;
        }

        if (isset($_SERVER[$param])) {
            return $_SERVER[$param];
        }

        return null;
    }

    /**
     * @return array
     */
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
