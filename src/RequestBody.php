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

use Psr\Http\Message\StreamInterface;

/**
 * Class RequestBody
 * @package DCarbone\PHPConsulAPI
 */
class RequestBody implements StreamInterface
{
    /** @var null|resource */
    private $h = null;

    /** @var int */
    private $size = 0;

    /**
     * RequestBody constructor.
     * @param mixed $contents
     * @param string $encoding
     */
    public function __construct($contents, $encoding = '8bit')
    {
        $str = '';
        switch(gettype($contents))
        {
            case 'integer':
            case 'double':
                fwrite($this->h, (string)$contents);
                break;

            case 'string':
                fwrite($this->h, $contents);
                break;

            case 'object':
            case 'array':
                fwrite($this->h, json_encode($contents));
                break;

            case 'boolean':
                fwrite($this->h, $contents ? 'true' : 'false');
                break;
        }

        $this->h = fopen('php://memory', 'w+');
        fwrite($this->h, $str);
        $this->size = mb_strlen($str, $encoding);
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        if ('resource' === gettype($this->h))
        {
            rewind($this->h);
            return fread($this->h, $this->size);
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        if ('resource' === gettype($this->h))
            fclose($this->h);
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        if ('resource' === gettype($this->h))
        {
            $h = $this->h;
            $this->h = null;
            return $h;
        }

        $this->h = null;
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
        if ('resource' === gettype($this->h))
            return ftell($this->h);

        return 0;
    }

    /**
     * @inheritDoc
     */
    public function eof()
    {
        if ('resource' === gettype($this->h))
            return feof($this->h);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function isSeekable()
    {
        return (bool)$this->getMetadata('seekable');
    }

    /**
     * @inheritDoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if ('resource' === gettype($this->h))
        {
            if (false === fseek($this->h, $offset, $whence))
            {
                throw new \RuntimeException('Unable execute seek, please verify values are in line with PHP fseek'.
                                            ' arguments: http://www.php.net/manual/en/function.fseek.php');
            }
        }
        else
        {
            throw new \RuntimeException('The underlying stream has been closed or is in an unstable state.  Seek'.
                                        ' operations cannot be performed.');
        }
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        if ('resource' === gettype($this->h))
        {
            if ($this->isSeekable())
            {
                if (false === rewind($this->h))
                    throw new \RuntimeException('Unable to rewind stream, something very bad has happened...');
            }
            else
            {
                throw new \RuntimeException('Stream is not seekable, cannot perform rewind.');
            }
        }
        else
        {
            throw new \RuntimeException('The underlying stream has been closed or is in an unstable state.  Rewind'.
                                        ' operations cannot be performed.');
        }
    }

    /**
     * @inheritDoc
     */
    public function isWritable()
    {
        $mode = $this->getMetadata('mode');
        if (null === $mode)
            return false;

        $c = substr($mode, 0, 1);
        return 0 === strpos($mode, 'r+')
               || 'w' === $c
               || 'a' === $c
               || 'x' === $c
               || 'c' === $c;
    }

    /**
     * @inheritDoc
     */
    public function write($string)
    {
        if ($this->isWritable())
            return fwrite($this->h, $string);

        throw new \RuntimeException(sprintf(
            'Stream is not in writable state, unable to write %s.',
            substr($string, 0, 500)
        ));
    }

    /**
     * @inheritDoc
     */
    public function isReadable()
    {
        $mode = $this->getMetadata('mode');
        if (null === $mode)
            return false;

        $c = substr($mode, 0, 1);
        $c2 = substr($mode, 0, 2);
        return 'r' === $c
               || 'w+' === $c2
               || 'a+' === $c2
               || 'x+' === $c2
               || 'c+' === $c2;
    }

    /**
     * @inheritDoc
     */
    public function read($length)
    {
        if ($this->isReadable())
        {
            if (feof($this->h))
                return '';

            return (string)fread($this->h, $length);
        }

        throw new \RuntimeException('Stream is not in readable state.');
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        if ($this->isReadable())
        {
            if (feof($this->h))
                return '';

            $contents = '';
            while (false !== ($d = fread($this->h, 8192)))
            {
                $contents = sprintf('%s%s', $contents, $d);
            }
            return $contents;
        }

        throw new \RuntimeException('Stream is not in readable state.');
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
        if ('resource' === gettype($this->h))
        {
            $m = stream_get_meta_data($this->h);

            if (null === $key)
                return $m;

            if (isset($m[$key]))
                return $m[$key];
        }

        return null;
    }
}