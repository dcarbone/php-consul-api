<?php namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use Http\Client\HttpClient;

/**
 * Class Config
 * @package DCarbone\PHPConsulAPI
 */
class Config
{
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
     * @var \Http\Client\HttpClient
     */
    public $HttpClient = null;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        foreach($config as $k => $v)
        {
            $this->{"set{$k}"}($v);
        }

        if (!isset($this->HttpAuth))
            $this->HttpAuth = new HttpAuth();
    }

    /**
     * Construct a configuration object from Environment Variables while using a specific HTTP Client
     *
     * @param HttpClient $client
     * @return Config
     */
    public static function newDefaultConfigWithClient(HttpClient $client)
    {
        $conf = new static([
            'Address' => '127.0.0.1:8500',
            'Scheme' => 'http',
            'HttpClient' => $client
        ]);

        $envParams = static::getEnvironmentConfig();
        if (isset($envParams['CONSUL_HTTP_ADDR']))
            $conf->setAddress($envParams['CONSUL_HTTP_ADDR']);

        if (isset($envParams['CONSUL_HTTP_TOKEN']))
            $conf->setToken($envParams['CONSUL_HTTP_TOKEN']);

        if (isset($envParams['CONSUL_HTTP_AUTH']))
            $conf->setHttpAuth($envParams['CONSUL_HTTP_AUTH']);

        if (isset($envParams['CONSUL_HTTP_SSL']) && $envParams['CONSUL_HTTP_SSL'])
            $conf->setScheme('https');

        if (isset($envParams['CONSUL_HTTP_SSL_VERIFY']) && !$envParams['CONSUL_HTTP_SSL_VERIFY'])
            $conf->setInsecureSkipVerify(false);

        return $conf;
    }

    /**
     * Construct a configuration object from Environment Variables and also attempt to locate an HTTP Client ot use.
     *
     * @return Config
     */
    public static function newDefaultConfig()
    {
        static $knownClients = array(
            '\\Http\\Client\\Curl\\Client',
            '\\Http\\Adapter\\Guzzle6\\Client',
            '\\Http\\Adapter\\Guzzle5\\Client',
            '\\Http\\Adapter\\Buzz\\Client'
        );

        foreach($knownClients as $clientClass)
        {
            if (class_exists($clientClass, true))
                return static::newDefaultConfigWithClient(new $clientClass);
        }

        throw new \RuntimeException(sprintf(
            '%s - Unable to determine HttpClient to use for default config',
            get_called_class()
        ));
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->Address;
    }

    /**
     * @param string $Address
     * @return Config
     */
    public function setAddress($Address)
    {
        $this->Address = $Address;
        return $this;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->Scheme;
    }

    /**
     * @param string $Scheme
     * @return Config
     */
    public function setScheme($Scheme)
    {
        $this->Scheme = $Scheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return $this->Datacenter;
    }

    /**
     * @param string $Datacenter
     * @return Config
     */
    public function setDatacenter($Datacenter)
    {
        $this->Datacenter = $Datacenter;
        return $this;
    }

    /**
     * @return int
     */
    public function getWaitTime()
    {
        return $this->WaitTime;
    }

    /**
     * @param int $WaitTime
     * @return Config
     */
    public function setWaitTime($WaitTime)
    {
        $this->WaitTime = $WaitTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->Token;
    }

    /**
     * @param string $Token
     * @return Config
     */
    public function setToken($Token)
    {
        $this->Token = $Token;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isInsecureSkipVerify()
    {
        return $this->InsecureSkipVerify;
    }

    /**
     * @param boolean $InsecureSkipVerify
     * @return Config
     */
    public function setInsecureSkipVerify($InsecureSkipVerify)
    {
        $this->InsecureSkipVerify = $InsecureSkipVerify;
        return $this;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\HttpAuth
     */
    public function getHttpAuth()
    {
        return $this->HttpAuth;
    }

    /**
     * @param string|HttpAuth $httpAuth
     * @return Config
     */
    public function setHttpAuth($httpAuth)
    {
        if (is_string($httpAuth))
        {
            $colon = strpos($httpAuth, ':');
            if (false === $colon)
            {
                $username = $httpAuth;
                $password = null;
            }
            else
            {
                $username = substr($httpAuth, 0, $colon);
                $password = substr($httpAuth, $colon + 1);
            }
            $httpAuth = new HttpAuth($username, $password);
        }

        if ($httpAuth instanceof HttpAuth)
        {
            $this->HttpAuth = $httpAuth;
            return $this;
        }

        throw new \InvalidArgumentException(sprintf(
            '%s::setHttpAuth - Value is expected to be string of "username:password" or instance of "ConsulHttpAuth", %s seen.',
            get_class($this),
            is_string($httpAuth) ? $httpAuth : gettype($httpAuth)
        ));
    }

    /**
     * @return \Http\Client\HttpClient
     */
    public function getHttpClient()
    {
        return $this->HttpClient;
    }

    /**
     * @param HttpClient $HttpClient
     * @return Config
     */
    public function setHttpClient(HttpClient $HttpClient)
    {
        $this->HttpClient = $HttpClient;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTokenInHeader()
    {
        return $this->TokenInHeader;
    }

    /**
     * @param boolean $TokenInHeader
     * @return Config
     */
    public function setTokenInHeader($TokenInHeader)
    {
        $this->TokenInHeader = (bool)$TokenInHeader;
        return $this;
    }

    /**
     * @return array
     */
    public static function getEnvironmentConfig()
    {
        return array_filter([
            'CONSUL_HTTP_ADDR' => static::_tryGetEnvParam('CONSUL_HTTP_ADDR'),
            'CONSUL_HTTP_AUTH' => static::_tryGetEnvParam('CONSUL_HTTP_AUTH'),
            'CONSUL_HTTP_SSL' => static::_tryGetEnvParam('CONSUL_HTTP_SSL'),
            'CONSUL_HTTP_SSL_VERIFY' => static::_tryGetEnvParam('CONSUL_HTTP_SSL_VERIFY')
        ], function($val) { return null !== $val; });
    }

    /**
     * @param string $param
     * @return string|null
     */
    protected static function _tryGetEnvParam($param)
    {
        if (false !== ($value = getenv($param)))
            return $value;

        if (isset($_SERVER[$param]))
            return $_SERVER[$param];

        return null;
    }
}