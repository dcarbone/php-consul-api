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


use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Stream as Psr7Stream;

/**
 * Class Request
 * @package DCarbone\PHPConsulAPI\Http
 */
class Request {
    /** @var \DCarbone\PHPConsulAPI\Config */
    private $config;
    /** @var \DCarbone\PHPConsulAPI\Params */
    public $params;
    /** @var string */
    private $path;

    /** @var \DCarbone\PHPConsulAPI\Uri */
    private $uri;

    /** @var string */
    private $method = 'POST';

    /** @var array */
    private $headers = ['Accept' => ['application/json'], 'Content-Type' => ['application/json']];

    /** @var null|resource */
    private $body = null;

    /**
     * Request constructor.
     * @param string $method
     * @param string $path
     * @param Config $config
     * @param string $body
     */
    public function __construct($method, $path, Config $config, $body = null) {
        $this->config = $config;

        $this->params = new Params();
        $this->method = strtoupper($method);
        $this->path = $path;

        if ('' !== ($dc = $config->getDatacenter())) {
            $this->params['dc'] = $dc;
        }

        if (0 !== ($wait = $config->getWaitTime())) {
            $this->params['wait'] = $wait;
        }

        if ('' !== ($token = $config->getToken())) {
            if ($config->isTokenInHeader()) {
                $this->headers['X-Consul-Token'] = $token;
            } else {
                $this->params['token'] = $token;
            }
        }

        if (null !== $body) {
            $str = '';
            switch (gettype($body)) {
                case 'object':
                case 'array':
                    $str = json_encode($body);
                    break;

                case 'integer':
                case 'double':
                    $str = (string)$body;
                    break;

                case 'string':
                    $str = $body;
                    break;

                case 'boolean':
                    $str = $body ? 'true' : 'false';
                    break;
            }
            $this->body = fopen('php://memory', 'w+');
            fwrite($this->body, $str);
        }
    }

    /**
     * Attempt to close body stream, if set.
     */
    public function __destruct() {
        if (isset($this->body) && 'resource' === gettype($this->body)) {
            @fclose($this->body);
        }
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $queryOptions
     */
    public function setQueryOptions(QueryOptions $queryOptions = null) {
        if (null === $queryOptions) {
            return;
        }

        if ('' !== ($dc = $queryOptions->getDatacenter())) {
            $this->params['dc'] = $dc;
        }

        if ($queryOptions->getAllowStale()) {
            $this->params['stale'] = '';
        }

        if ($queryOptions->getRequireConsistent()) {
            $this->params['consistent'] = '';
        }

        if (0 !== ($waitIndex = $queryOptions->getWaitIndex())) {
            $this->params['index'] = $waitIndex;
        }

        if (0 !== ($waitTime = $queryOptions->getWaitTime())) {
            $this->params['wait'] = $waitTime;
        }

        if ('' !== ($token = $queryOptions->getToken())) {
            if ($this->config->isTokenInHeader()) {
                $this->headers['X-Consul-Token'] = $token;
            } else {
                $this->params['token'] = $token;
            }
        }

        if ('' !== ($near = $queryOptions->getNear())) {
            $this->params['near'] = $near;
        }
    }

    /**
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $writeOptions
     */
    public function setWriteOptions(WriteOptions $writeOptions = null) {
        if (null === $writeOptions) {
            return;
        }

        if ('' !== ($dc = $writeOptions->getDatacenter())) {
            $this->params['dc'] = $dc;
        }

        if ('' !== ($token = $writeOptions->getToken())) {
            $this->params['token'] = $token;
        }
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Uri
     */
    public function getUri() {
        if (!isset($this->uri)) {
            $this->uri = new Uri($this->path, $this->config, $this->params);
        }

        return $this->uri;
    }

    /**
     * Constructs a Psr7 compliant request for use in a Psr7 client.
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    public function toPsrRequest() {
        return new Psr7Request(
            $this->method,
            $this->getUri(),
            $this->headers,
            isset($this->body) ? new Psr7Stream($this->body) : null
        );
    }
}
