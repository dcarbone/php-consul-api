<?php namespace DCarbone\SimpleConsulPHP;

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
 * Class AbstractConsulClient
 * @package DCarbone\SimpleConsulPHP\Base
 */
abstract class AbstractConsulClient
{
    /** @var string */
    private $_lastUrl = null;
    /** @var array */
    private $_lastInfo = array();
    /** @var string */
    private $_lastError = '';

    /** @var ConsulConfig */
    private $_config;

    /** @var array */
    private static $_defaultCurlOpts = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLINFO_HEADER_OUT => true,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
        )
    );

    /** @var array */
    private $_curlOpts = array();

    /**
     * AbstractConsulClient constructor.
     * @param ConsulConfig $config
     */
    public function __construct(ConsulConfig $config = null)
    {
        if (null === $config)
            $config = ConsulConfig::newDefaultConfig();

        $this->_config = $config;
    }

    /**
     * @return string
     */
    public function getLastUrl()
    {
        return $this->_lastUrl;
    }

    /**
     * @return array
     */
    public function getLastInfo()
    {
        return $this->_lastInfo;
    }

    /**
     * @return int
     */
    public function getLastHttpCode()
    {
        return isset($this->_lastInfo['http_code']) ? $this->_lastInfo['http_code'] : 0;
    }

    /**
     * @return string
     */
    public function getLastError()
    {
        return $this->_lastError;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param QueryOptions $queryOptions
     * @param string $body
     * @return array|null
     */
    protected function execute($method, $uri, QueryOptions $queryOptions = null, $body = null)
    {
        if (!is_string($method))
            throw new \InvalidArgumentException(sprintf('%s - Method must be string', get_class($this), gettype($method)));

        if ('' === ($method = trim($method)))
            throw new \InvalidArgumentException(sprintf('%s - Method must be non-empty string', get_class($this)));

        if (null === $queryOptions)
            $queryOptions = new QueryOptions();

        $this->_addConfigQueryOptions($queryOptions);

        $this->_curlOpts = self::$_defaultCurlOpts + $this->_config->getCurlOptArray();

        switch(strtolower($method))
        {
            case 'get':
                $this->_lastUrl = $this->_GET($uri, $queryOptions);
                break;
            case 'put':
                $this->_lastUrl = $this->_PUT($uri, $queryOptions, $body);
                break;
            case 'delete':
                $this->_lastUrl = $this->_DELETE($uri, $queryOptions, $body);
                break;

            default:
                throw new \UnexpectedValueException(sprintf(
                    '%s - SimpleConsulPHP currently does not support queries made using the "%s" method.',
                    get_class($this),
                    $method
                ));
        };

        $ch = curl_init($this->_lastUrl);

        if (!curl_setopt_array($ch, $this->_curlOpts))
        {
            throw new \DomainException(sprintf(
                '%s - Unable to set specified Curl options, please ensure you\'re passing in valid constants.  Specified options: %s',
                get_class($this),
                json_encode($this->_curlOpts)
            ));
        }

        $data = curl_exec($ch);
        $this->_lastInfo = curl_getinfo($ch);
        $this->_lastError = curl_error($ch);
        curl_close($ch);

        return $this->parseResponse($data);
    }

    /**
     * @param string|bool $data
     * @return array
     */
    protected function parseResponse($data)
    {
        if (is_string($data))
        {
            if (200 === $this->_lastInfo['http_code'])
            {
                $data = @json_decode($data, true);
                $err = json_last_error();

                if (JSON_ERROR_NONE === $err)
                    return $data;

                throw new \DomainException(sprintf(
                    '%s - Unable to parse response as JSON.  Message: %s',
                    get_class($this),
                    PHP_VERSION_ID >= 50500 ? json_last_error_msg() : (string)$err
                ));
            }

            if (404 === $this->_lastInfo['http_code'])
                return null;

            if ('' === $data)
            {
                throw new \UnexpectedValueException(sprintf(
                    '%s - Error seen while executing "%s".  Response code: %d.  Message: %s',
                    get_class($this),
                    $this->_lastUrl,
                    $this->_lastInfo['http_code'],
                    $this->_lastError
                ));
            }

            throw new \UnexpectedValueException(sprintf(
                '%s - Error seen while executing "%s": %s.',
                get_class($this),
                $this->_lastUrl,
                $data
            ));
        }

        throw new \UnexpectedValueException(sprintf(
            '%s - Invalid response seen executing query "%s": %s',
            get_class($this),
            $this->_lastUrl,
            $this->_lastError
        ));
    }

    /**
     * @param QueryOptions $queryOptions
     */
    private function _addConfigQueryOptions(QueryOptions $queryOptions)
    {
        if (null !== ($token = $this->_config->getToken()) && null === $queryOptions->getToken())
            $queryOptions->setToken($token);

        if (null !== ($dc = $this->_config->getDatacenter()) && null === $queryOptions->getDatacenter())
            $queryOptions->setDatacenter($dc);
    }
    
    /**
     * @param string $uri
     * @param QueryOptions $queryOptions
     * @return string
     */
    private function _GET($uri, QueryOptions $queryOptions)
    {
        $this->_curlOpts[CURLOPT_HTTPGET] = true;

        return $this->_buildUrl($uri, $queryOptions);
    }

    /**
     * @param string $uri
     * @param QueryOptions $queryOptions
     * @param string $body
     * @return string
     */
    private function _PUT($uri, QueryOptions $queryOptions, $body = null)
    {
        $this->_curlOpts[CURLOPT_CUSTOMREQUEST] = 'PUT';

        if (null !== $body)
            $this->_curlOpts[CURLOPT_POSTFIELDS] = $body;

        return $this->_buildUrl($uri, $queryOptions);
    }

    /**
     * @param string $uri
     * @param QueryOptions $queryOptions
     * @param string $body
     * @return string
     */
    private function _DELETE($uri, QueryOptions $queryOptions, $body = null)
    {
        $this->_curlOpts[CURLOPT_CUSTOMREQUEST] = 'DELETE';

        if (null !== $body)
            $this->_curlOpts[CURLOPT_POSTFIELDS] = $body;

        return $this->_buildUrl($uri, $queryOptions);
    }

    /**
     * @param string $uri
     * @param QueryOptions $queryOptions
     * @return string
     */
    private function _buildUrl($uri, QueryOptions $queryOptions)
    {
        return sprintf(
            '%s/%s?%s',
            $this->_config->compileAddress(),
            ltrim(trim($uri), "/"),
            $queryOptions
        );
    }
}