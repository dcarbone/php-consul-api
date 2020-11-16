<?php namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;

/**
 * Class Config
 * @package DCarbone\PHPConsulAPI
 */
class Config
{
    private const DefaultConfig = [
        'Address' => '127.0.0.1:8500',
        'Scheme' => 'http',
    ];

    /**
     * The address, including port, of your Consul Agent
     *
     * @var string
     */
    public $Address = '';

    /**
     * The scheme to use.  Currently only HTTP and HTTPS are supported.
     *
     * @var string
     */
    public $Scheme = '';

    /**
     * The name of the datacenter you wish all queries to be made against by default
     *
     * @var string
     */
    public $Datacenter = '';

    /**
     * HTTP authentication, if used
     *
     * @var \DCarbone\PHPConsulAPI\HttpAuth
     */
    public $HttpAuth = null;

    /**
     * Time to wait on certain blockable endpoints
     *
     * @var int
     */
    public $WaitTime = 0;

    /**
     * ACL token to use by default
     *
     * @var string
     */
    public $Token = '';

    /**
     * File containing the current token to use for this client.
     *
     * If provided, it is read once at startup and never again
     *
     * @var string
     */
    public $TokenFile = '';

    /**
     * Optional path to CA certificate
     *
     * @var string
     */
    public $CAFile = '';

    /**
     * Optional path to certificate.  If set, KeyFile must also be set
     *
     * @var string
     */
    public $CertFile = '';

    /**
     * Optional path to private key.  If set, CertFile must also be set
     *
     * @var string
     */
    public $KeyFile = '';

    /**
     * Whether to skip SSL validation.  This does nothing unless you use it within your HttpClient of choice.
     *
     * @var bool
     */
    public $InsecureSkipVerify = false;

    /**
     * Whether to use Consul 0.7.0-style X-Consul-Token header or the older query-param style for passing ACL tokens
     *
     * @var bool
     */
    public $TokenInHeader = true;

    /**
     * Your HttpClient of choice.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    public $HttpClient = null;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach ($config + self::_getDefaultConfig() as $k => $v) {
            $this->{"set{$k}"}($v);
        }

        if (null !== $this->HttpAuth && !isset($this->HttpAuth)) {
            $this->HttpAuth = new HttpAuth();
        }

        // quick validation on key/cert combo
        $c = $this->getCertFile();
        $k = $this->getKeyFile();
        if (('' !== $k && '' === $c) || ('' !== $c && '' === $k)) {
            throw new \InvalidArgumentException(sprintf(
                '%s - CertFile and KeyFile must be both either empty or populated.  Key: %s; Cert: %s',
                get_class($this),
                $k,
                $c
            ));
        }

        // if client hasn't been constructed, construct.
        if (null === $this->HttpClient) {
            $this->HttpClient = new Client();
        }
    }

    /**
     * @param \DCarbone\PHPConsulAPI\Config|null $inc
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public static function merge(?Config $inc): Config
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
        if (null !== $inc->HttpAuth) {
            $actual->HttpAuth = clone($inc->HttpAuth);
        }
        if (0 !== $inc->WaitTime) {
            $actual->WaitTime = $inc->WaitTime;
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
        if ($inc->TokenInHeader) {
            $actual->TokenInHeader = true;
        }
        if (null !== $inc->HttpClient) {
            $actual->HttpClient = $inc->HttpClient;
        }
        return $actual;
    }

    /**
     * Construct a configuration object from Environment Variables and use bare guzzle client instance
     *
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public static function newDefaultConfig(): Config
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
    public function setAddress(string $address): Config
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
    public function setScheme(string $scheme): Config
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
    public function setDatacenter(string $datacenter): Config
    {
        $this->Datacenter = $datacenter;
        return $this;
    }

    /**
     * @return int
     */
    public function getWaitTime(): int
    {
        return $this->WaitTime;
    }

    /**
     * @param int $waitTime
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setWaitTime(int $waitTime): Config
    {
        $this->WaitTime = $waitTime;
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
    public function setToken(string $token): Config
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
     * @param string $TokenFile
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setTokenFile(string $TokenFile): Config
    {
        $this->TokenFile = $TokenFile;
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
    public function setInsecureSkipVerify(bool $insecureSkipVerify): Config
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
     * @param string|HttpAuth $httpAuth
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setHttpAuth($httpAuth): Config
    {
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

        if ($httpAuth instanceof HttpAuth) {
            $this->HttpAuth = $httpAuth;
            return $this;
        }

        throw new \InvalidArgumentException(sprintf(
            '%s::setHttpAuth - Value is expected to be string of "username:password" or instance of "\\DCarbone\\PHPConsulApi\\HttpAuth", %s seen.',
            get_class($this),
            is_string($httpAuth) ? $httpAuth : gettype($httpAuth)
        ));
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
    public function setCAFile(string $caFile): Config
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
    public function setCertFile(string $certFile): Config
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
    public function setKeyFile(string $keyFile): Config
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
    public function setHttpClient(ClientInterface $httpClient): Config
    {
        $this->HttpClient = $httpClient;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTokenInHeader(): bool
    {
        return $this->TokenInHeader;
    }

    /**
     * @param bool $tokenInHeader
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setTokenInHeader(bool $tokenInHeader): Config
    {
        $this->TokenInHeader = $tokenInHeader;
        return $this;
    }

    /**
     * @param int $in
     * @return string
     */
    public function intToMillisecond(int $in): string
    {
        if (!is_int($in)) {
            throw new \InvalidArgumentException(sprintf('$in must be integer, saw "%s".', gettype($in)));
        }

        $ms = intval($in / 1000000, 10);

        if (0 < $in && 0 === $ms) {
            $ms = 1;
        }

        return sprintf('%dms', $ms);
    }

    /**
     * @return array
     */
    public function getGuzzleRequestOptions(): array
    {
        // TODO: Define once?
        $opts = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::DECODE_CONTENT => false,
        ];

        if (!$this->isInsecureSkipVerify()) {
            $opts[RequestOptions::VERIFY] = false;
        } else if ('' !== ($b = $this->getCAFile())) {
            $opts[RequestOptions::VERIFY] = $b;
        }

        if ('' !== ($c = $this->getCertFile())) {
            $opts[RequestOptions::CERT] = $c;
            $opts[RequestOptions::SSL_KEY] = $this->getKeyFile();
        }

        return $opts;
    }

    /**
     * @param string $param
     * @return string|null
     */
    protected static function _tryGetEnvParam(string $param)
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

    /**
     * @return array
     */
    public static function getEnvironmentConfig(): array
    {
        $ret = [];
        foreach ([
                     Consul::HTTPAddrEnvName => static::_tryGetEnvParam(Consul::HTTPAddrEnvName),
                     Consul::HTTPTokenEnvName => static::_tryGetEnvParam(Consul::HTTPTokenEnvName),
                     Consul::HTTPTokenFileEnvName => static::_tryGetEnvParam(Consul::HTTPTokenFileEnvName),
                     Consul::HTTPAuthEnvName => static::_tryGetEnvParam(Consul::HTTPAuthEnvName),
                     Consul::HTTPCAFileEnvName => static::_tryGetEnvParam(Consul::HTTPCAFileEnvName),
                     Consul::HTTPClientCertEnvName => static::_tryGetEnvParam(Consul::HTTPClientCertEnvName),
                     Consul::HTTPClientKeyEnvName => static::_tryGetEnvParam(Consul::HTTPClientKeyEnvName),
                     Consul::HTTPSSLEnvName => static::_tryGetEnvParam(Consul::HTTPSSLEnvName),
                     Consul::HTTPSSLVerifyEnvName => static::_tryGetEnvParam(Consul::HTTPSSLVerifyEnvName),
                 ] as $k => $v) {
            if (null !== $v) {
                $ret[$k] = $v;
            }
        }
        return $ret;
    }

    /**
     * @return array
     */
    private static function _getDefaultConfig(): array
    {
        $conf = self::DefaultConfig;

        // parse env vars
        foreach (static::getEnvironmentConfig() as $k => $v) {
            if (Consul::HTTPAddrEnvName === $k) {
                $conf['Address'] = $v;
            } else if (Consul::HTTPTokenEnvName === $k) {
                $conf['Token'] = $v;
            } else if (Consul::HTTPTokenFileEnvName) {
                $conf['TokenFile'] = $v;
            } else if (Consul::HTTPAuthEnvName === $k) {
                $conf['HttpAuth'] = $v;
            } else if (Consul::HTTPCAFileEnvName === $k) {
                $conf['CAFile'] = $v;
            } else if (Consul::HTTPClientCertEnvName === $k) {
                $conf['CertFile'] = $v;
            } else if (Consul::HTTPClientKeyEnvName === $k) {
                $conf['KeyFile'] = $v;
            } else if (Consul::HTTPSSLEnvName === $k) {
                if ((bool)$v) {
                    $conf['Scheme'] = 'https';
                }
            } else if (Consul::HTTPSSLVerifyEnvName === $k) {
                if ((bool)$v) {
                    $conf['InsecureSkipVerify'] = true;
                }
            }
        }

        return $conf;
    }
}