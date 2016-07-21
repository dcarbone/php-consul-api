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

/**
 * Class Config
 * @package DCarbone\PHPConsulAPI
 */
class Config extends AbstractStrictCollection
{
    /**
     * @return array
     */
    protected function getDefinition()
    {
        return array(
            'Address' => null,
            'Scheme' => null,
            'Datacenter' => null,
            'HttpAuth' => null,
            'WaitTime' => null,
            'Token' => null,
            'CAFile' => null,
            'CertFile' => null,
            'KeyFile' => null,
            'InsecureSkipVerify' => null
        );
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
        return (string)$this->_storage['Address'];
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->_storage['Address'] = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return (string)$this->_storage['Scheme'];
    }

    /**
     * @param string $scheme
     * @return $this
     */
    public function setScheme($scheme)
    {
        $this->_storage['Scheme'] = $scheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatacenter()
    {
        return (string)$this->_storage['Datacenter'];
    }

    /**
     * @param string $datacenter
     * @return $this
     */
    public function setDatacenter($datacenter)
    {
        $this->_storage['Datacenter'] = $datacenter;
        return $this;
    }

    /**
     * @return HttpAuth
     */
    public function getHttpAuth()
    {
        return $this->_storage['HttpAuth'];
    }

    /**
     * @param string|HttpAuth $httpAuth
     * @return $this
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
            $this->_storage['HttpAuth'] = $httpAuth;
            return $this;
        }

        throw new \InvalidArgumentException(sprintf(
            '%s::setHttpAuth - Value is expected to be string of "username:password" or instance of "ConsulHttpAuth", %s seen.',
            get_class($this),
            is_string($httpAuth) ? $httpAuth : gettype($httpAuth)
        ));
    }

    /**
     * @return int
     */
    public function getWaitTime()
    {
        return (int)$this->_storage['WaitTime'];
    }

    /**
     * @param int $waitTime
     * @return $this
     */
    public function setWaitTime($waitTime)
    {
        $this->_storage['WaitTime'] = (int)$waitTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return (string)$this->_storage['Token'];
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->_storage['Token'] = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getCAFile()
    {
        return (string)$this->_storage['CAFile'];
    }

    /**
     * @param string $caFile Filepath to CA File
     * @return $this
     */
    public function setCAFile($caFile)
    {
        $this->_storage['CAFile'] = $caFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertFile()
    {
        return (string)$this->_storage['CertFile'];
    }

    /**
     * @param string $certFile Filepath to certificate file
     * @return $this
     */
    public function setCertFile($certFile)
    {
        $this->_storage['CertFile'] = $certFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeyFile()
    {
        return (string)$this->_storage['KeyFile'];
    }

    /**
     * @param string $keyFile Filepath to certificate key file
     * @return $this
     */
    public function setKeyFile($keyFile)
    {
        $this->_storage['KeyFile'] = $keyFile;
        return $this;
    }

    /**
     * @return bool
     */
    public function getInsecureSkipVerify()
    {
        return (bool)$this->_storage['InsecureSkipVerify'];
    }

    /**
     * @param bool $insecureSkipVerify
     * @return $this
     */
    public function setInsecureSkipVerify($insecureSkipVerify)
    {
        $this->_storage['InsecureSkipVerify'] = (bool)$insecureSkipVerify;
        return $this;
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
    public function getCurlOptArray()
    {
        $opts = array();

        if ($auth = $this->getHttpAuth())
            $opts[CURLOPT_HTTPAUTH] = (string)$auth;

        if ($this->getInsecureSkipVerify())
        {
            $opts[CURLOPT_SSL_VERIFYPEER] = false;
            $opts[CURLOPT_SSL_VERIFYHOST] = false;
        }

        if ($caFile = $this->getCAFile())
            $opts[CURLOPT_CAINFO] = $caFile;

        if ($certFile = $this->getCertFile())
            $opts[CURLOPT_SSLCERT] = $certFile;

        if ($keyFile = $this->getKeyFile())
            $opts[CURLOPT_SSLKEY] = $keyFile;

        $opts[CURLOPT_TIMEOUT] = $this->getWaitTime();

        return $opts;
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

        return false;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by json_encode, which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        if (isset($data['HttpAuth']))
            unset($data['HttpAuth']);

        return $data;
    }
}