<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2020 Daniel Carbone (daniel.p.carbone@gmail.com)

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
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Request
 * @package DCarbone\PHPConsulAPI
 */
class Request
{
    /** @var \DCarbone\PHPConsulAPI\Values */
    public $Headers;
    /** @var \DCarbone\PHPConsulAPI\Params */
    public $params;

    /** @var \DCarbone\Go\Time\Duration|null */
    public $Timeout = null;

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
     * @param \DCarbone\PHPConsulAPI\Config $config
     * @param mixed $body
     */
    public function __construct(string $method, string $path, Config $config, $body = null)
    {
        $this->config = $config;

        $this->method = strtoupper($method);
        $this->path = $path; // TODO: perform some kind of path input validation?

        $this->Headers = new Values();
        $this->params = new Params();

        if ('' !== $config->Datacenter) {
            $this->params->set('dc', $config->Datacenter);
        }

        if (0 !== $config->WaitTime) {
            $this->params->set('wait', $config->intToMillisecond($config->WaitTime));
        }

        if ('' !== $config->Token) {
            $this->Headers->set('X-Consul-Token', $config->Token);
        }

        if (null !== $body) {
            $str = '';
            switch (gettype($body)) {
                case 'object':
                case 'array':
                    $str = json_encode($body, $config->JSONEncodeOpts);
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
    public function __destruct()
    {
        if (isset($this->body) && 'resource' === gettype($this->body)) {
            @fclose($this->body);
        }
    }

    /**
     * @param \DCarbone\PHPConsulAPI\QueryOptions|null $opts
     */
    public function setQueryOptions(?QueryOptions $opts): void
    {
        if (null === $opts) {
            return;
        }

        if ('' !== $opts->Namespace) {
            $this->params->set('ns', $opts->Namespace);
        }
        if ('' !== $opts->Datacenter) {
            $this->params->set('dc', $opts->Datacenter);
        }
        if ($opts->AllowStale) {
            $this->params->set('stale', '');
        }
        if ($opts->RequireConsistent) {
            $this->params->set('consistent', '');
        }
        if (0 !== $opts->WaitIndex) {
            $this->params->set('index', (string)$opts->WaitIndex);
        }
        if (0 !== $opts->WaitTime) {
            $this->params->set('wait', $this->config->intToMillisecond($opts->WaitTime));
        }
        if ('' !== $opts->WaitHash) {
            $this->params->set('hash', $opts->WaitHash);
        }
        if ('' !== $opts->Token) {
            $this->Headers->set('X-Consul-Token', $opts->Token);
        }
        if ('' !== $opts->Near) {
            $this->params->set('near', $opts->Near);
        }
        if ('' !== $opts->Filter) {
            $this->params->set('filter', $opts->Filter);
        }
        if (isset($opts->NodeMeta) && [] !== $opts->NodeMeta) {
            foreach ($opts->NodeMeta as $k => $v) {
                $this->params->add('node-meta', "{$k}:{$v}");
            }
        }
        if (0 !== $opts->RelayFactor) {
            $this->params->set('relay-factor', (string)$opts->RelayFactor);
        }
        if ($opts->LocalOnly) {
            $this->params->set('local-only', 'true');
        }
        if ($opts->Connect) {
            $this->params->set('connect', 'true');
        }
        if ($opts->UseCache && !$opts->RequireConsistent) {
            $this->params->set('cached', '');
            $cc = [];
            if (null !== $opts->MaxAge) {
                $cc[] = sprintf('max-age=%.0f', $opts->MaxAge->Seconds());
            }
            if (null !== $opts->StaleIfError) {
                $cc[] = sprintf('stale-if-error=%.0f', $opts->StaleIfError->Seconds());
            }
            if ([] !== $cc) {
                $this->Headers->set('Cache-Control', implode(', ', $cc));
            }
        }

        if (null !== $opts->Timeout) {
            $this->Timeout = $opts->Timeout;
        }

        if ($opts->Pretty) {
            $this->params->set('pretty', '');
        }

        $this->uri = null;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\WriteOptions|null $opts
     */
    public function setWriteOptions(WriteOptions $opts = null): void
    {
        if (null === $opts) {
            return;
        }

        if ('' !== $opts->Namespace) {
            $this->params->set('ns', $opts->Namespace);
        }
        if ('' !== $opts->Datacenter) {
            $this->params->set('dc', $opts->Datacenter);
        }
        if ('' !== $opts->Token) {
            $this->Headers->set('X-Consul-Token', $opts->Token);
        }
        if (0 !== $opts->RelayFactor) {
            $this->params->set('relay-factor', (string)$opts->RelayFactor);
        }

        if (null !== $opts->Timeout) {
            $this->Timeout = $opts->Timeout;
        }

        $this->uri = null;
    }

    /**
     * @param string $filter
     */
    public function filterQuery(string $filter = ''): void {
        if ('' === $filter) {
            return;
        }
        $this->params->set('filter', $filter);
    }

    /**
     * @return \Psr\Http\Message\UriInterface
     */
    public function getUri(): UriInterface
    {
        if (!isset($this->uri)) {
            $uri = sprintf(
                '%s://%s/%s',
                $this->config->getScheme(),
                $this->config->Address,
                ltrim(
                    rtrim($this->path, " \t\n\r\0\x0B&?"),
                    " \t\n\r\0\x0B/"
                ) // TODO: Lessen # of things being looked for?
            );
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
    public function toPsrRequest(): RequestInterface
    {
        return new Psr7Request(
            $this->method,
            $this->getUri(),
            $this->Headers->toPsr7Array(),
            isset($this->body) ? new Psr7Stream($this->body) : null
        );
    }
}
