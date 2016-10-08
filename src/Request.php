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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Request
 * @package DCarbone\PHPConsulAPI\Http
 */
class Request implements RequestInterface
{
    /** @var Params */
    public $params;
    /** @var mixed */
    public $body = null;

    /** @var StreamInterface|null */
    private $compiledBody = null;

    /** @var string */
    private $protocolVersion = '1.1';

    /** @var string */
    private $method;

    /** @var string */
    private $path;

    /** @var string */
    private $requestTarget = null;

    /** @var Uri */
    private $uri = null;

    /** @var array */
    private $headers = array(
        'Content-Type' => ['application/json'],
        'Accept' => ['application/json'],
    );
    /** @var array */
    private $_normalizedHeaderNameMap = array(
        'content-type' => 'Content-Type',
        'Accept' => 'accept'
    );

    /** @var Config */
    private $c;

    /**
     * Request constructor.
     * @param string $method
     * @param string $path
     * @param Config $config
     * @param string $body
     */
    public function __construct($method, $path, Config $config, $body = null)
    {
        $this->c = $config;

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
        {
            if ($this->c->isTokenInHeader())
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
        $this->protocolVersion = $version;
        return $this;
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
        return isset($this->_normalizedHeaderNameMap[strtolower($name)]);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        $name = strtolower($name);
        if (isset($this->_normalizedHeaderNameMap[$name]))
            return $this->headers[$this->_normalizedHeaderNameMap[$name]];

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
        $name = strtolower($name);
        if (isset($this->_normalizedHeaderNameMap[$name]))
            return implode(',', $this->headers[$this->_normalizedHeaderNameMap[$name]]);

        return '';
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        if (!is_array($value))
            $value = [(string)$value];

        $clone = clone $this;

        $clone->headers[$name] = $value;
        $clone->_normalizedHeaderNameMap[strtolower($name)] = $name;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        $lower = strtolower($name);
        if (isset($this->_normalizedHeaderNameMap[$lower]))
            $name = $this->_normalizedHeaderNameMap[$lower];

        if (!is_array($value))
            $value = [(string)$value];

        $clone = clone $this;

        if (isset($this->headers[$name]))
        {
            $clone->headers[$name] = array_unique(array_merge($this->headers[$name], $value));
        }
        else
        {
            $clone->headers[$name] = $value;
            $clone->_normalizedHeaderNameMap[strtolower($name)] = $name;
        }

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        $lower = strtolower($name);

        $clone = clone $this;

        if (isset($clone->_normalizedHeaderNameMap[$lower]))
            unset($clone->headers[$this->_normalizedHeaderNameMap[$lower]], $clone->_normalizedHeaderNameMap[$lower]);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        if (!isset($this->compiledBody))
            $this->compiledBody = new RequestBody($this->body);

        return $this->compiledBody;
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->compiledBody = $body;
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
        $clone = clone $this;
        $clone->method = strtoupper($method);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        if (null === $this->uri)
            $this->uri = new Uri($this->path, $this->c, $this->params);

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

        if (!$preserveHost)
        {
            // TODO: Do this...
        }

        return $clone;
    }
}