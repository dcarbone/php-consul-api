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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Request
 * @package DCarbone\PHPConsulAPI\Http
 */
class Request implements RequestInterface
{
    // PHPConsulAPI properties

    /** @var \DCarbone\PHPConsulAPI\Config */
    private $config;
    /** @var \DCarbone\PHPConsulAPI\Params */
    public $params;
    /** @var string */
    private $path;

    /** @var array */
    private $_normalizedHeaders = ['accept' => 'Accept', 'content-type' => 'Content-Type'];
    /** @var StreamInterface|null */
    private $_compiledBody = null;

    // PSR-7 properties below

    /** @var string */
    private $protocolVersion = '1.1';
    /** @var array */
    private $headers = ['Accept' => ['application/json'], 'Content-Type' => ['application/json']];
    /** @var \Psr\Http\Message\StreamInterface */
    private $body = null;
    /** @var string */
    private $requestTarget = null;
    /** @var string */
    private $method = 'POST';
    /** @var \Psr\Http\Message\UriInterface */
    private $uri = null;

    /**
     * Request constructor.
     * @param string $method
     * @param string $path
     * @param Config $config
     * @param string $body
     */
    public function __construct($method, $path, Config $config, $body = null)
    {
        $this->config = $config;

        $this->params = new Params();
        $this->method = strtoupper($method);
        $this->path = $path;

        if ('' !== ($dc = $config->getDatacenter()))
            $this->params['dc'] = $dc;

        if (0 !== ($wait = $config->getWaitTime()))
            $this->params['wait'] = $wait;

        if ('' !== ($token = $config->getToken()))
        {
            if ($config->isTokenInHeader())
                $this->headers['X-Consul-Token'] = $token;
            else
                $this->params['token'] = $token;
        }

        $this->body = $body;
    }

    public function __clone()
    {
        $this->_compiledBody = null;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $queryOptions
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
        {
            if ($this->config->isTokenInHeader())
                $this->headers['X-Consul-Token'] = $token;
            else
                $this->params['token'] = $token;
        }

        if ('' !== ($near = $queryOptions->getNear()))
            $this->params['near'] = $near;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $writeOptions
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
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
        return isset($this->_normalizedHeaders[strtolower($name)]);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        $lower = strtolower($name);
        if (!isset($this->_normalizedHeaders[$lower]))
            return [];

        return $this->headers[$this->_normalizedHeaders[$lower]];
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
        $lower = strtolower($name);
        if (!isset($this->_normalizedHeaders[$name]))
            return '';

        return implode(',', $this->headers[$this->_normalizedHeaders[$lower]]);
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        $type = gettype($value);
        if ('string' !== $type && 'array' !== $type)
            throw new \InvalidArgumentException(sprintf('$value must be array or string, %s seen.', gettype($value)));

        $lower = strtolower($name);

        $clone = clone $this;
        $clone->_normalizedHeaders[$lower] = $name;

        if ('string' === $type)
            $clone->headers[$name] = [$value];
        else
            $clone->headers[$name] = $value;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        $type = gettype($value);
        if ('string' !== $type && 'array' !== $type)
            throw new \InvalidArgumentException('$value must be array or string, %s seen.', gettype($value));

        $lower = strtolower($name);

        if (isset($this->_normalizedHeaders[$lower]))
            $headerValues = $this->headers[$this->_normalizedHeaders[$lower]];
        else
            $headerValues = [];

        if ('string' === $type)
            $headerValues[] = $value;
        else
            $headerValues = array_merge($headerValues, $value);

        $clone = clone $this;

        $clone->_normalizedHeaders[$lower] = $name;
        $clone->headers[$name] = $headerValues;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        $clone = clone $this;

        $lower = strtolower($name);
        if (isset($clone->_normalizedHeaders[$lower]))
            unset($clone->headers[$clone->_normalizedHeaders[$lower]], $clone->_normalizedHeaders[$lower]);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        if (isset($this->body) && !isset($this->_compiledBody))
            $this->_compiledBody = new RequestBody($this->body);

        return $this->_compiledBody;
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget()
    {
        if (null === $this->requestTarget)
        {
            $uri = $this->getUri();
            $p = $uri->getPath();
            $q = $uri->getQuery();

            if ('' === $p)
                $t = '/';
            else
                $t = $p;

            if ('' !== $q)
                $t = sprintf('%s?%s', $t, $q);

            $this->requestTarget = $t;
        }

        return $this->requestTarget;
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        $clone = clone $this;
        $clone->requestTarget = trim($requestTarget);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method)
    {
        static $allowable = ['GET', 'PUT', 'POST', 'DELETE'];
        $upper = strtoupper($method);
        if (in_array($upper, $allowable, true))
        {
            $clone = clone $this;
            $clone->method = $upper;
            return $clone;
        }

        throw new \InvalidArgumentException(
            '"%s" is not an allowable request method.  Allowable: ["%s"]',
                $upper,
                implode('", "', $allowable)
        );
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        if (null === $this->uri)
            $this->uri = new Uri($this->path, $this->config, $this->params);

        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        if ($uri === $this->uri)
            return $this;

        $clone = clone $this;
        $clone->uri = $uri;

        if ($preserveHost)
            $clone->uri = $this->uri->withHost($this->uri->getHost());

        return $clone;
    }
}
