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
 * Class HttpRequest
 * @package DCarbone\PHPConsulAPI
 */
class HttpRequest
{
    /** @var Params */
    public $params;
    /** @var string */
    public $body = null;

    /** @var Config */
    private $_Config;

    /** @var string */
    public $method;
    /** @var string */
    public $path;
    /** @var string */
    public $url;

    /** @var string */
    private $_compiledBody = null;

    /** @var array */
    private $_curlOpts = array();
    /** @var array */
    private static $_defaultCurlOpts = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLINFO_HEADER_OUT => true,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
        ),
        CURLOPT_HEADER => true,
    );

    /**
     * Request constructor.
     * @param string $method
     * @param string $path
     * @param Config $config
     * @param string $body
     */
    public function __construct($method, $path, Config $config, $body = null)
    {
        $this->_Config = $config;

        $this->params = new Params();
        $this->method = strtolower($method);
        $this->path = $path;

        if ('' !== ($dc = $config->getDatacenter()))
            $this->params['dc'] = $dc;

        if (0 !== ($wait = $config->getWaitTime()))
            $this->params['wait'] = $wait;

        if ('' !== ($token = $config->getToken()))
            $this->params['token'] = $token;

        $this->_curlOpts = self::$_defaultCurlOpts + $config->getCurlOptArray();

        $this->body = $body;
    }

    /**
     * @param QueryOptions|null $queryOptions
     */
    public function setQueryOptions(QueryOptions $queryOptions = null)
    {
        if (null === $queryOptions)
            return;
        
        if ('' !== ($dc = $queryOptions->getDatacenter()))
            $this->params['dc'] = $dc;

        if ($queryOptions->getAllowStale())
            $this->params['stale'] = '';

        if ($queryOptions->getRequireConsistent())
            $this->params['consistent'] = '';

        if (0 !== ($waitIndex = $queryOptions->getWaitIndex()))
            $this->params['index'] = $waitIndex;
        
        if (0 !== ($waitTime = $queryOptions->getWaitTime()))
            $this->params['wait'] = $waitTime;

        if ('' !== ($token = $queryOptions->getToken()))
            $this->params['token'] = $token;

        if ('' !== ($near = $queryOptions->getNear()))
            $this->params['near'] = $near;
    }

    /**
     * @param WriteOptions|null $writeOptions
     */
    public function setWriteOptions(WriteOptions $writeOptions = null)
    {
        if (null === $writeOptions)
            return;

        if ('' !== ($dc = $writeOptions->getDatacenter()))
            $this->params['dc'] = $dc;

        if ('' !== ($token = $writeOptions->getToken()))
            $this->params['token'] = $token;
    }

    /**
     * @return string
     */
    public function getCompiledBody()
    {
        if (!isset($this->_compiledBody))
        {
            switch(gettype($this->body))
            {
                case 'integer':
                case 'double':
                    $this->_compiledBody = (string)$this->body;
                    break;

                case 'string':
                    $this->_compiledBody = $this->body;
                    break;

                case 'object':
                case 'array':
                    $this->_compiledBody = json_encode($this->body);
                    break;

                case 'boolean':
                    $this->_compiledBody = $this->body ? 'true' : 'false';
                    break;

                default:
                    $this->_compiledBody = '';
            }
        }

        return $this->_compiledBody;
    }

    /**
     * @return array(
     *  @type HttpResponse|null response or null on error
     *  @type \DCarbone\PHPConsulAPI\Error|null any error if seen
     * )
     */
    public function execute()
    {
        if (0 === count($this->params))
        {
            $this->url = sprintf(
                '%s/%s',
                $this->_Config->compileAddress(),
                ltrim(trim($this->path), "/")
            );
        }
        else
        {
            $this->url = sprintf(
                '%s/%s?%s',
                $this->_Config->compileAddress(),
                ltrim(trim($this->path), "/"),
                $this->params
            );
        }

        Logger::debug(sprintf(
            'Executing %s request %s%s',
            $this->method,
            $this->url,
            $this->body ? sprintf(' with body "%s"', $this->getCompiledBody()) : ''
        ));

        switch($this->method)
        {
            case 'get':
                // no prep needed
                break;
            case 'put':
                $this->_preparePUT();
                break;
            case 'delete':
                $this->_prepareDELETE();
                break;

            default:
                return [null, new Error(sprintf(
                    '%s - PHPConsulAPI currently does not support queries made using the "%s" method.',
                    get_class($this),
                    $this->method
                ))];
        }

        $ch = curl_init($this->url);

        if (false === $ch)
        {
            $err = new Error(sprintf(
                '%s::execute - Unable to initialize CURL resource with URL "%s"',
                get_class($this),
                $this->url
            ));
            Logger::log('error', $err);
            return [null, $err];
        }

        if (false === curl_setopt_array($ch, $this->_curlOpts))
        {
            $err = new Error(sprintf(
                '%s - Unable to set specified Curl options, please ensure you\'re passing in valid constants.  Specified options: %s',
                get_class($this),
                json_encode($this->_curlOpts)
            ));
            Logger::error($err);
            return [null, $err];
        }

        $response = new HttpResponse(curl_exec($ch), curl_getinfo($ch), curl_error($ch));

        curl_close($ch);

        Logger::debug('Response received - Code: '.$response->httpCode.'; Body: '.$response->body.'; ');

        return [$response, null];
    }

    private function _preparePUT()
    {
        $this->_curlOpts[CURLOPT_CUSTOMREQUEST] = 'PUT';
        $this->_curlOpts[CURLOPT_POSTFIELDS] = $this->getCompiledBody();
    }

    private function _prepareDELETE()
    {
        $this->_curlOpts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $this->_curlOpts[CURLOPT_POSTFIELDS] = $this->getCompiledBody();
    }
}