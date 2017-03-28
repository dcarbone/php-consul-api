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
    const STATE_OPEN = 0;
    const STATE_CLOSED = 1;
    const STATE_DETACHED = 2;

    /** @var int */
    private $_state;

    /** @var null|resource */
    private $stream = null;
    /** @var null|array */
    private $meta = null;
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
                $str = (string)$contents;
                break;

            case 'string':
                $str = $contents;
                break;

            case 'object':
            case 'array':
                $str = json_encode($contents);
                break;

            case 'boolean':
                $str = $contents ? 'true' : 'false';
                break;
        }

        $this->stream = fopen('php://memory', 'w+');
        fwrite($this->stream, $str);

        $this->size = mb_strlen($str, $encoding);

        $this->meta = stream_get_meta_data($this->stream);

        $this->_state = self::STATE_OPEN;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        if (null === $this->stream)
            return '';


        $this->rewind();
        $str = '';
        while(!$this->eof() && $data = $this->read(8192))
        {
            $str .= $data;
        }

        return $str;
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        if (self::STATE_OPEN === $this->_state)
        {
            fclose($this->stream);
            $this->stream = null;
            $this->size = 0;
            $this->_state = self::STATE_CLOSED;
        }
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        if (self::STATE_OPEN === $this->_state)
            $stream = $this->stream;
        else
            $stream = null;

        $this->stream = null;
        $this->size = 0;
        $this->_state = self::STATE_DETACHED;
        return $stream;
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {
        if (self::STATE_OPEN !== $this->_state)
            return null;

        return $this->size;
    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
        if (self::STATE_OPEN === $this->_state)
            return ftell($this->stream);

        throw new \RuntimeException();
    }

    /**
     * @inheritDoc
     */
    public function eof()
    {
        if (null === $this->stream)
            return true;

        return feof($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable()
    {
        return self::STATE_OPEN === $this->_state;
    }

    /**
     * @inheritDoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if ($this->isSeekable())
            fseek($this->stream, $offset, $whence);
        else
            throw $this->createStateException(__METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        if ($this->isSeekable())
            rewind($this->stream);
        else
            throw $this->createStateException(__METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function isWritable()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function write($string)
    {
        throw new \RuntimeException('PHPConsulAPI request bodies are immutable.');
    }

    /**
     * @inheritDoc
     */
    public function isReadable()
    {
        return self::STATE_OPEN === $this->_state;
    }

    /**
     * @inheritDoc
     */
    public function read($length)
    {
        if (self::STATE_OPEN === $this->_state)
            return (string)fread($this->stream, $length);

        throw $this->createStateException(__METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        if (self::STATE_OPEN === $this->_state)
        {
            $str = '';
            while(!$this->eof() && $data = $this->read(8192))
            {
                $str .= $data;
            }

            return $str;
        }

        throw $this->createStateException(__METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
        if (self::STATE_OPEN === $this->_state)
        {
            if (null === $key)
                return $this->meta;

            if (isset($this->meta[$key]))
                return $this->meta[$key];

            return null;
        }

        return null;
    }

    /**
     * @param string $action
     * @return \RuntimeException
     */
    private function createStateException($action)
    {
        if (self::STATE_DETACHED === $this->_state)
            return new \RuntimeException(sprintf('Cannot "%s", request body is in a detached state', $action));

        return new \RuntimeException('Cannot "%s", request body is closed', $action);
    }
}
