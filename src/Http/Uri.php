<?php namespace DCarbone\PHPConsulAPI\Http;

use DCarbone\PHPConsulAPI\Config;
use Psr\Http\Message\UriInterface;

/**
 * Class Uri
 * @package DCarbone\PHPConsulAPI\Http
 */
class Uri implements UriInterface, \JsonSerializable
{
    /** @var string */
    private $scheme = '';

    /** @var HttpAuth */
    private $userInfo;

    /** @var string */
    private $host = '';

    /** @var int */
    private $port = null;

    /** @var string */
    private $path = '';

    /** @var string */
    private $query = '';

    /** @var string */
    private $fragment = '';

    /**
     * Uri constructor.
     * @param string $path
     * @param Config $c
     * @param Params $p
     */
    public function __construct($path, Config $c, Params $p)
    {
        $this->scheme = $c->Scheme;
        $this->userInfo = $c->HttpAuth;
        $this->path = $path;

        $a = $c->Address;

        if (false === ($pos = strpos($a, ':')))
        {
            $this->host = $a;
        }
        else
        {
            $this->host = substr($a, 0, $pos);
            $this->port = (int)substr($a, $pos + 1);
        }

        $this->query = (string)$p;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return [
            'scheme',
            'userInfo',
            'host',
            'port',
            'path',
            'query',
            'fragment'
        ];
    }

    public function __clone()
    {
        $this->userInfo = clone $this->userInfo;
    }

    /**
     * @inheritDoc
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getAuthority()
    {
        $ui = $this->getUserInfo();
        $h = $this->getHost();
        $p = $this->getPort();

        if ('' === $ui)
            $uri = $h;
        else
            $uri = sprintf('%s@%s', $ui, $h);

        if (null === $p)
            return $uri;

        return sprintf('%s:%d', $uri, $p);
    }

    /**
     * @inheritDoc
     */
    public function getUserInfo()
    {
        return (string)$this->userInfo;
    }

    /**
     * @inheritDoc
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    public function withScheme($scheme)
    {
        $clone = clone $this;
        $clone->scheme = trim((string)$scheme, " \t\n\r\0\x0B:/");
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo($user, $password = null)
    {
        $clone = clone $this;
        $clone->userInfo = new HttpAuth($user, $password);
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHost($host)
    {
        $clone = clone $this;
        $clone->host = trim((string)$host, " \t\n\r\0\x0B/");
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withPort($port)
    {
        $clone = clone $this;
        $clone->port = null === $port ? null : (int)$port;
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withPath($path)
    {
        $clone = clone $this;
        $clone->path = trim((string)$path, " \t\n\r\0\x0B");
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query)
    {
        $clone = clone $this;
        $clone->query = trim((string)$query, " \t\n\r\0\x0B?");
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment)
    {
        $clone = clone $this;
        $clone->fragment = trim((string)$fragment, " \t\r\n\0\x0B#");
        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $s = $this->getScheme();
        $a = $this->getAuthority();
        $p = $this->getPath();
        $q = $this->getQuery();
        $f = $this->getFragment();

        if ('' === $s)
            $uri = '';
        else
            $uri = sprintf('%s:', $s);

        if ('' !== $a)
            $uri = sprintf('%s//%s', $uri, $a);

        if ('' !== $p)
        {
            if (0 === strpos($p, '/'))
            {
                if ('' === $a)
                    $uri = sprintf('%s/%s', $uri, $p);
                else
                    $uri = sprintf('%s%s', $uri, $p);
            }
            else
            {
                if ('' === $a)
                    $uri = sprintf('%s/%s', $uri, ltrim($p, "/"));
                else
                    $uri = sprintf('%s%s', $uri, $p);
            }
        }

        if ('' !== $q)
            $uri = sprintf('%s?%s', $uri, $q);

        if ('' !== $f)
            $uri = sprintf('%s#%s', $uri, $f);

        return $uri;
    }

    /**
     * @inheritDoc
     */
    function jsonSerialize()
    {
        return [
            'scheme' => $this->scheme,
            'userInfo' => $this->userInfo,
            'host' => $this->host,
            'port' => $this->port,
            'path' => $this->path,
            'query' => $this->query,
            'fragment' => $this->fragment
        ];
    }
}