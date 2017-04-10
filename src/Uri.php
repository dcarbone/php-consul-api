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

use Psr\Http\Message\UriInterface;

/**
 * Class Uri
 * @package DCarbone\PHPConsulAPI\Http
 */
class Uri implements UriInterface {
    /** @var string */
    private $scheme = '';
    /** @var string */
    private $host = '';
    /** @var int */
    private $port = 0;
    /** @var string */
    private $path = '';
    /** @var string */
    private $query = '';
    /** @var string */
    private $fragment = '';
    /** @var string */
    private $userInfo = '';

    /** @var string */
    private $_compiled = null;

    /**
     * Uri constructor.
     * @param string $path
     * @param Config $config
     * @param Params $params
     */
    public function __construct($path, Config $config, Params $params) {
        $this->scheme = $config->Scheme;
        $this->userInfo = $config->HttpAuth;
        $this->path = $path;

        $a = $config->Address;

        if (false === ($pos = strpos($a, ':'))) {
            $this->host = $a;
        } else {
            $this->host = substr($a, 0, $pos);
            $this->port = (int)substr($a, $pos + 1);
        }

        $this->query = (string)$params;
    }

    public function __clone() {
        $this->_compiled = null;
    }

    /**
     * @inheritDoc
     */
    public function getScheme() {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getAuthority() {
        $ui = $this->getUserInfo();
        $host = $this->getHost();
        $port = $this->getPort();

        if ('' === $ui) {
            $uri = $host;
        } else {
            $uri = sprintf('%s@%s', $ui, $host);
        }

        if (null === $port || 0 === $port) {
            return $uri;
        }

        return sprintf('%s:%d', $uri, $port);
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo() {
        return (string)$this->userInfo;
    }

    /**
     * @inheritDoc
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function getFragment() {
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    public function withScheme($scheme) {
        $scheme = strtolower($scheme);
        if ('http' === $scheme || 'https' === $scheme) {
            $clone = clone $this;
            $clone->scheme = $scheme;
            return $clone;
        }

        throw new \InvalidArgumentException(sprintf('Scheme must be "http" or "https", saw "%s"', $scheme));
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo($user, $password = null) {
        $clone = clone $this;
        $clone->userInfo = new HttpAuth($user, $password);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHost($host) {
        if (null === $host) {
            $clone = clone $this;
            $clone->host = '';
            return $clone;
        }

        // TODO: Some kind of hostname validation
        $clone = clone $this;
        $clone->host = $host;
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withPort($port) {
        if (null !== $port && (!is_int($port) || 0 >= $port || 65535 < $port)) {
            throw new \InvalidArgumentException(
                sprintf('Port must be integer greater than 0 and less than 65535, saw "%s"',
                    is_int($port) ? (string)$port : gettype($port))
            );
        }

        $clone = clone $this;
        $clone->port = $port;
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withPath($path) {
        if (is_string($path)) {
            $clone = clone $this;
            $clone->path = ltrim($path, "/");
            return $clone;
        }

        if (null === $path) {
            $clone = clone $this;
            $clone->path = '';
            return $clone;
        }

        throw new \InvalidArgumentException(sprintf('Path must be string or null, saw "%s"', gettype($path)));
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query) {
        // TODO: Some validation...?
        if (null === $query) {
            $clone = clone $this;
            $clone->query = '';
            return $clone;
        }

        if (is_string($query)) {
            $clone = clone $this;
            $clone->query = $query;
            return $clone;
        }

        throw new \InvalidArgumentException(sprintf('Query must be string or null, saw "%s"', gettype($query)));
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment) {
        $clone = clone $this;
        $clone->fragment = trim((string)$fragment, " \t\r\n\0\x0B#");
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function __toString() {
        if (null === $this->_compiled) {
            $s = $this->getScheme();
            $a = $this->getAuthority();
            $p = $this->getPath();
            $q = $this->getQuery();
            $f = $this->getFragment();

            if ('' === $s) {
                $uri = '';
            } else {
                $uri = sprintf('%s:', $s);
            }

            if ('' !== $a) {
                $uri = sprintf('%s//%s', $uri, $a);
            }

            if ('' !== $p) {
                if (0 === strpos($p, '/')) {
                    if ('' === $a) {
                        $uri = sprintf('%s/%s', $uri, $p);
                    } else {
                        $uri = sprintf('%s%s', $uri, $p);
                    }
                } else {
                    if ('' === $a) {
                        $uri = sprintf('%s/%s', $uri, ltrim($p, "/"));
                    } else {
                        $uri = sprintf('%s/%s', $uri, $p);
                    }
                }
            }

            if ('' !== $q) {
                $uri = sprintf('%s?%s', $uri, $q);
            }

            if ('' !== $f) {
                $uri = sprintf('%s#%s', $uri, $f);
            }

            $this->_compiled = $uri;
        }

        return $this->_compiled;
    }
}