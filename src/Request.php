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
use GuzzleHttp\Psr7\Uri;

/**
 * Class Request
 * @package DCarbone\PHPConsulAPI
 */
class Request {
    /** @var \DCarbone\PHPConsulAPI\Values */
    public $headers;
    /** @var \DCarbone\PHPConsulAPI\Params */
    public $params;

    /** @var \DCarbone\PHPConsulAPI\Config */
    private $config;

    /** @var string */
    private $path;

    /** @var \Psr\Http\Message\UriInterface */
    private $uri;

    /** @var string */
    private $method;

    /** @var null|resource */
    private $body;

    /**
     * Request constructor.
     * @param string $method
     * @param string $path
     * @param Config $config
     * @param string $body
     */
    public function __construct($method, $path, Config $config, $body = null) {
        $this->config = $config;

        $this->method = strtoupper($method);
        $this->path = $path;

        $this->headers = new Values();
        $this->params = new Params();

        if ('' !== $config->Datacenter) {
            $this->params->set('dc', $config->Datacenter);
        }

        if (0 !== $config->WaitTime) {
            $this->params->set('wait', $config->intToMillisecond($config->WaitTime));
        }

        if ('' !== $config->Token) {
            if ($config->TokenInHeader) {
                $this->headers->set('X-Consul-Token', $config->Token);
            } else {
                $this->params->set('token', $config->Token);
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
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $options
     */
    public function setQueryOptions(QueryOptions $options = null) {
        if (null === $options) {
            return;
        }

        if ('' !== $options->Datacenter) {
            $this->params->set('dc', $options->Datacenter);
        }
        if ($options->AllowStale) {
            $this->params->set('stale', '');
        }
        if ($options->RequireConsistent) {
            $this->params->set('consistent', '');
        }
        if (0 !== $options->WaitIndex) {
            $this->params->set('index', (string)$options->WaitIndex);
        }
        if (0 !== $options->WaitTime) {
            $this->params->set('wait', $this->config->intToMillisecond($options->WaitTime));
        }
        if ('' !== $options->Token) {
            if ($this->config->TokenInHeader) {
                $this->headers->set('X-Consul-Token', $options->Token);
            } else {
                $this->params->set('token', $options->Token);
            }
        }
        if ('' !== $options->Near) {
            $this->params->set('near', $options->Near);
        }
        if (isset($options->NodeMeta) && 0 < count($options->NodeMeta)) {
            foreach ($options->NodeMeta as $k => $v) {
                $this->params->add('node-meta', "{$k}:{$v}");
            }
        }
        if ('' !== $options->RelayFactor) {
            $this->params->set('relay-factor', (string)$options->RelayFactor);
        }

        $this->uri = null;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $options
     */
    public function setWriteOptions(WriteOptions $options = null) {
        if (null === $options) {
            return;
        }

        if ('' !== $options->Datacenter) {
            $this->params->set('dc', $options->Datacenter);
        }
        if ('' !== $options->Token) {
            if ($this->config->TokenInHeader) {
                $this->headers->set('X-Consul-Token', $options->Token);
            } else {
                $this->headers->set('token', $options->Token);
            }
        }
        if (0 !== $options->RelayFactor) {
            $this->params->set('relay-factor', (string)$options->RelayFactor);
        }

        $this->uri = null;
    }

    /**
     * @return \Psr\Http\Message\UriInterface
     */
    public function getUri() {
        if (!isset($this->uri)) {
            $uri = sprintf('%s://%s/%s', $this->config->getScheme(), $this->config->Address, $this->path);
            if (0 < count($this->params)) {
                $uri = sprintf('%s?%s', $uri, (string)$this->params);
            }
            $this->uri = new Uri($uri);
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
            $this->headers->toPsr7Array(),
            isset($this->body) ? new Psr7Stream($this->body) : null
        );
    }
}
