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

use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;

/**
 * Class Config
 * @package DCarbone\PHPConsulAPI
 */
class Config
{
    /** @var string */
    public $Address = '';
    /** @var string */
    public $Scheme = '';
    /** @var string */
    public $Datacenter = '';
    /** @var null */
    public $HttpAuth = null;
    /** @var int */
    public $WaitTime = 0;
    /** @var string */
    public $Token = '';
    /** @var string */
    public $CAFile = '';
    /** @var string */
    public $CertFile = '';
    /** @var string */
    public $KeyFile = '';
    /** @var bool */
    public $InsecureSkipVerify = false;

    /** @var HttpClient */
    public $HttpClient = null;
    /** @var HttpAsyncClient */
    public $HttpAsyncClient = null;

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
    }

    /**
     * @return Config
     */
    public static function newDefaultConfig()
    {
        $conf = new static([
            'Address' => '127.0.0.1:8500',
            'Scheme' => 'http'
        ]);

        if (false !== ($addr = static::_tryGetEnvParam('CONSUL_HTTP_ADDR')))
            $conf->setAddress($addr);

        if (false !== ($token = static::_tryGetEnvParam('CONSUL_HTTP_TOKEN')))
            $conf->setToken($token);

        if (false !== ($auth = static::_tryGetEnvParam('CONSUL_HTTP_AUTH')))
            $conf->setHttpAuth($auth);

        if ($ssl = (bool)static::_tryGetEnvParam('CONSUL_HTTP_SSL'))
            $conf->setScheme('https');

        if ($doVerify = (bool)static::_tryGetEnvParam('CONSUL_HTTP_SSL_VERIFY'))
            $conf->setInsecureSkipVerify(!$doVerify);

        return $conf;
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
     * @return string
     */
    public function getCAFile()
    {
        return $this->CAFile;
    }

    /**
     * @param string $CAFile
     * @return Config
     */
    public function setCAFile($CAFile)
    {
        $this->CAFile = $CAFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertFile()
    {
        return $this->CertFile;
    }

    /**
     * @param string $CertFile
     * @return Config
     */
    public function setCertFile($CertFile)
    {
        $this->CertFile = $CertFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeyFile()
    {
        return $this->KeyFile;
    }

    /**
     * @param string $KeyFile
     * @return Config
     */
    public function setKeyFile($KeyFile)
    {
        $this->KeyFile = $KeyFile;
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
     * @return null
     */
    public function getHttpAuth()
    {
        return $this->HttpAuth;
    }

    /**
     * @return HttpClient
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
     * @return HttpAsyncClient
     */
    public function getHttpAsyncClient()
    {
        return $this->HttpAsyncClient;
    }

    /**
     * @param HttpAsyncClient $HttpAsyncClient
     * @return Config
     */
    public function setHttpAsyncClient(HttpAsyncClient $HttpAsyncClient)
    {
        $this->HttpAsyncClient = $HttpAsyncClient;
        return $this;
    }

    /**
     * @param RequestInterface $request
     * @param bool $useAsync
     * @return \Http\Promise\Promise|\Psr\Http\Message\ResponseInterface
     */
    public function doRequest(RequestInterface $request, $useAsync = false)
    {
        if ($useAsync)
        {
            if (isset($this->HttpAsyncClient))
                return $this->HttpAsyncClient->sendAsyncRequest($request);

            throw new \RuntimeException('Unable to execute Async query as no HttpAsyncClient has been defined.');
        }

        if (isset($this->HttpClient))
            return $this->HttpClient->sendRequest($request);

        throw new \RuntimeException('Unable to execute query as no HttpClient has been defined.');
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
     * @return string
     */
    public function compileAddress()
    {
        if ('' === ($scheme = $this->getScheme()))
        {
            throw new \LogicException(sprintf(
                '%s - "Scheme" was left undefined in this config object. Definition: %s',
                get_class($this),
                json_encode($this)
            ));
        }

        if ('' === ($addr = $this->getAddress()))
        {
            throw new \LogicException(sprintf(
                '%s - "Address" was left undefined in this config object. Definition: %s',
                get_class($this),
                json_encode($this)
            ));
        }

        return sprintf('%s://%s', $scheme, $addr);
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
     * @return string|bool
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