<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\Go\Time;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Request
 */
class Request
{
    /** @var \DCarbone\PHPConsulAPI\Values */
    public Values $header;
    /** @var \DCarbone\PHPConsulAPI\Params */
    public Params $params;

    /** @var \DCarbone\Go\Time\Duration|null */
    public ?Time\Duration $timeout = null;

    /** @var string */
    private string $scheme;
    /** @var string */
    private string $address;
    /** @var string */
    private string $path;

    /** @var string */
    private string $method;

    /** @var mixed */
    private $body;

    /**
     * Request constructor.
     * @param string $method
     * @param string $path
     * @param \DCarbone\PHPConsulAPI\Config $config
     * @param mixed $body
     */
    public function __construct(string $method, string $path, Config $config, $body)
    {
        $this->scheme  = $config->Scheme;
        $this->address = $config->Address;

        $this->method = \strtoupper($method);
        $this->path   = \ltrim(\rtrim($path, " \t\n\r\0\x0B&?"), " \t\n\r\0\x0B/");

        $this->header = new Values();
        $this->params = new Params();

        if ('' !== $config->Datacenter) {
            $this->params->set('dc', $config->Datacenter);
        }
        if ('' !== $config->Namespace) {
            $this->params->set('ns', $config->Namespace);
        }
        if (null !== $config->WaitTime && $config->WaitTime->Nanoseconds() > 0) {
            $this->params->set('wait', dur_to_millisecond($config->WaitTime));
        }
        if ('' !== $config->Token) {
            $this->header->set('X-Consul-Token', $config->Token);
        }

        $this->body = $body;

//        if (null !== $body) {
//            switch (\gettype($body)) {
//                case Hydration::OBJECT:
//                case Hydration::ARRAY:
//                    $this->body = \json_encode($body, $config->JSONEncodeOpts);
//                    if (\JSON_ERROR_NONE !== \json_last_error()) {
//                        throw new \RuntimeException(
//                            \sprintf(
//                                'Error encoding request body as json: %s',
//                                \json_last_error_msg()
//                            )
//                        );
//                    }
//                    break;
//
//                case Hydration::INTEGER:
//                case Hydration::DOUBLE:
//                    $this->body = (string)$body;
//                    break;
//
//                case Hydration::STRING:
//                    $this->body = $body;
//                    break;
//
//                case Hydration::BOOLEAN:
//                    $this->body = $body ? Hydration::TRUE : Hydration::FALSE;
//                    break;
//            }
//        }
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param \DCarbone\PHPConsulAPI\RequestOptions|null $opts
     */
    public function applyOptions(?RequestOptions $opts): void
    {
        if (null === $opts) {
            return;
        }
        $opts->apply($this);
    }

    /**
     * @param string $filter
     */
    public function filterQuery(string $filter): void
    {
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
        $uri = "{$this->scheme}://{$this->address}/{$this->path}";
        if (0 < \count($this->params)) {
            $uri .= "?{$this->params}";
        }
        return new Uri($uri);
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
            $this->header->toPsr7Array(),
            null
        );
    }

    /**
     * @return \DCarbone\PHPConsulAPI\RequestMeta
     */
    public function meta(): RequestMeta
    {
        return new RequestMeta($this->method, $this->getUri());
    }
}
