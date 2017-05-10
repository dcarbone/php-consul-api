<?php namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
class Config {
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
    public $TokenInHeader = false;

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
    public function __construct(array $config = []) {
        foreach ($config + self::getDefaultConfig() as $k => $v) {
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
    }

    /**
     * Construct a configuration object from Environment Variables and use bare guzzle client instance
     *
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public static function newDefaultConfig() {
        return new static(self::getDefaultConfig());
    }

    /**
     * @return array
     */
    private static function getDefaultConfig() {
        $conf = [
            'Address' => '127.0.0.1:8500',
            'Scheme' => 'http',
            'HttpClient' => new Client(),
        ];

        // parse env vars
        foreach (static::getEnvironmentConfig() as $k => $v) {
            switch ($k) {
                case Consul::HTTPAddrEnvName:
                    $conf['Address'] = $v;
                    break;
                case Consul::HTTPTokenEnvName:
                    $conf['Token'] = $v;
                    break;
                case Consul::HTTPAuthEnvName:
                    $conf['HttpAuth'] = $v;
                    break;
                case Consul::HTTPCAFileEnvName:
                    $conf['CAFile'] = $v;
                    break;
                case Consul::HTTPClientCertEnvName:
                    $conf['CertFile'] = $v;
                    break;
                case Consul::HTTPClientKeyEnvName:
                    $conf['KeyFile'] = $v;
                    break;
                case Consul::HTTPSSLEnvName:
                    if ((bool)$v) {
                        $conf['Scheme'] = 'https';
                    }
                    break;
                case Consul::HTTPSSLVerifyEnvName:
                    if ((bool)$v) {
                        $conf['InsecureSkipVerify'] = true;
                    }
                    break;
            }
        }

        return $conf;
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setAddress($Address) {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return string
     */
    public function getScheme() {
        return $this->Scheme;
    }

    /**
     * @param string $Scheme
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setScheme($Scheme) {
        $this->Scheme = $Scheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter() {
        return $this->Datacenter;
    }

    /**
     * @param string $Datacenter
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setDatacenter($Datacenter) {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return int
     */
    public function getWaitTime() {
        return $this->WaitTime;
    }

    /**
     * @param int $WaitTime
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setWaitTime($WaitTime) {
        $this->WaitTime = $WaitTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->Token;
    }

    /**
     * @param string $Token
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setToken($Token) {
        $this->Token = $Token;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isInsecureSkipVerify() {
        return $this->InsecureSkipVerify;
    }

    /**
     * @param boolean $InsecureSkipVerify
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setInsecureSkipVerify($InsecureSkipVerify) {
        $this->InsecureSkipVerify = $InsecureSkipVerify;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\HttpAuth
     */
    public function getHttpAuth() {
        return $this->HttpAuth;
    }

    /**
     * @param string|HttpAuth $HttpAuth
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setHttpAuth($HttpAuth) {
        if (is_string($HttpAuth)) {
            $colon = strpos($HttpAuth, ':');
            if (false === $colon) {
                $username = $HttpAuth;
                $password = null;
            } else {
                $username = substr($HttpAuth, 0, $colon);
                $password = substr($HttpAuth, $colon + 1);
            }
            $HttpAuth = new HttpAuth($username, $password);
        }

        if ($HttpAuth instanceof HttpAuth) {
            $this->HttpAuth = $HttpAuth;
            return $this;
        }

        throw new \InvalidArgumentException(sprintf(
            '%s::setHttpAuth - Value is expected to be string of "username:password" or instance of "\\DCarbone\\PHPConsulApi\\HttpAuth", %s seen.',
            get_class($this),
            is_string($HttpAuth) ? $HttpAuth : gettype($HttpAuth)
        ));
    }

    /**
     * @return string
     */
    public function getCAFile() {
        return $this->CAFile;
    }

    /**
     * @param string $CAFile
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setCAFile($CAFile) {
        $this->CAFile = $CAFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertFile() {
        return $this->CertFile;
    }

    /**
     * @param string $CertFile
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setCertFile($CertFile) {
        $this->CertFile = $CertFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeyFile() {
        return $this->KeyFile;
    }

    /**
     * @param string $KeyFile
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setKeyFile($KeyFile) {
        $this->KeyFile = $KeyFile;
        return $this;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient() {
        return $this->HttpClient;
    }

    /**
     * @param \GuzzleHttp\ClientInterface $HttpClient
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setHttpClient(ClientInterface $HttpClient) {
        $this->HttpClient = $HttpClient;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTokenInHeader() {
        return $this->TokenInHeader;
    }

    /**
     * @param boolean $TokenInHeader
     * @return \DCarbone\PHPConsulAPI\Config
     */
    public function setTokenInHeader($TokenInHeader) {
        $this->TokenInHeader = (bool)$TokenInHeader;
        return $this;
    }

    /**
     * @param int $in
     * @return string
     */
    public function intToMillisecond($in) {
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
    public function getGuzzleRequestOptions() {
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
     * @return array
     */
    public static function getEnvironmentConfig() {
        return array_filter([
            Consul::HTTPAddrEnvName => static::_tryGetEnvParam(Consul::HTTPAddrEnvName),
            Consul::HTTPAuthEnvName => static::_tryGetEnvParam(Consul::HTTPAuthEnvName),
            Consul::HTTPCAFileEnvName => static::_tryGetEnvParam(Consul::HTTPCAFileEnvName),
            Consul::HTTPClientCertEnvName => static::_tryGetEnvParam(Consul::HTTPClientCertEnvName),
            Consul::HTTPClientKeyEnvName => static::_tryGetEnvParam(Consul::HTTPClientKeyEnvName),
            Consul::HTTPSSLEnvName => static::_tryGetEnvParam(Consul::HTTPSSLEnvName),
            Consul::HTTPSSLVerifyEnvName => static::_tryGetEnvParam(Consul::HTTPSSLVerifyEnvName),
        ],
            function ($val) {
                return null !== $val;
            });
    }

    /**
     * @param string $param
     * @return string|null
     */
    protected static function _tryGetEnvParam($param) {
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
}