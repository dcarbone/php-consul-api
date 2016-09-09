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

/**
 * Class SocketLogger
 * @package DCarbone\PHPConsulAPI\Logging
 */
class SocketLogger implements ConsulAPILoggerInterface
{
    /** @var string */
    private $_address;
    /** @var int */
    private $_port;
    /** @var int */
    private $_domain;
    /** @var int */
    private $_type;
    /** @var int */
    private $_protocol;
    /** @var array */
    private $_options;
    /** @var int */
    private $_sendFlags;
    /** @var bool */
    private $_sendBlocking;

    /** @var resource */
    private $_socket;

    /** @var bool */
    private $_connected = false;

    /**
     * SocketLogger constructor.
     * @param string $address
     * @param int $port
     * @param int $domain
     * @param int $type
     * @param int $protocol
     * @param array $options
     * @param int $sendFlags
     * @param bool $sendBlocking
     */
    public function __construct($address,
                                $port = 0,
                                $domain = AF_INET,
                                $type = SOCK_DGRAM,
                                $protocol = SOL_UDP,
                                array $options = array(),
                                $sendFlags = 0,
                                $sendBlocking = false)
    {
        $this->_address = $address;

        if (!is_int($port) || 0 > $port)
        {
            throw new \InvalidArgumentException(sprintf(
                '%s - $port MUST be a positive integer, %s seen.',
                get_class($this),
                is_int($port) ? $port : gettype($port)
            ));
        }
        $this->_port = $port;

        if (AF_INET !== $domain && AF_INET6 !== $domain && AF_UNIX !== $domain)
        {
            throw new \InvalidArgumentException(sprintf(
                '%s - $domain MUST equal one of the AF_* constants.  See http://php.net/manual/en/function.socket-create.php for more information',
                get_class($this)
            ));
        }
        $this->_domain = $domain;

        if (SOCK_STREAM !== $type && SOCK_DGRAM !== $type && SOCK_SEQPACKET !== $type && SOCK_RAW !== $type && SOCK_RDM !== $type)
        {
            throw new \InvalidArgumentException(sprintf(
                '%s - $type must equal one of the SOCK_* constants.  See http://php.net/manual/en/function.socket-create.php for more information',
                get_class($this)
            ));
        }
        $this->_type = $type;

        $this->_protocol = $protocol;
        $this->_sendFlags = $sendFlags;

        if (!is_bool($sendBlocking))
        {
            throw new \InvalidArgumentException(sprintf(
                '%s - $sendBlocking MUST be boolean, %s seen.',
                get_class($this),
                gettype($sendBlocking)
            ));
        }
        $this->_sendBlocking = $sendBlocking;

        $this->_socket = socket_create($this->_domain, $this->_type, $this->_protocol);
        if (false === $this->_socket)
        {
            throw new \RuntimeException(sprintf(
                '%s - Unable to create socket: %s',
                get_class($this),
                socket_strerror(socket_last_error())
            ));
        }

        foreach($options as $opt => $value)
        {
            socket_set_option($this->_socket, $this->_protocol, $opt, $value);
        }

        $this->_options = $options;

        if ($this->_sendBlocking)
            socket_set_block($this->_socket);
        else
            socket_set_nonblock($this->_socket);
    }

    /**
     * Close socket on shutdown
     */
    public function __destruct()
    {
        if ('resource' === gettype($this->_socket))
            @socket_close($this->_socket);
    }

    /**
     * @param string $message
     * @return bool
     */
    public function error($message)
    {
        return $this->_log('error', $message);
    }

    /**
     * @param string $message
     * @return bool
     */
    public function warn($message)
    {
        return $this->_log('warn', $message);
    }

    /**
     * @param string $message
     * @return bool
     */
    public function info($message)
    {
        return $this->_log('info', $message);
    }

    /**
     * @param string $message
     * @return bool
     */
    public function debug($message)
    {
        return $this->_log('debug', $message);
    }

    /**
     * @param string $level
     * @param string $message
     * @return bool|int
     */
    private function _log($level, $message)
    {
        if (!$this->_connected && !socket_connect($this->_socket, $this->_address, $this->_port))
        {
            error_log(sprintf(
                '%s - Unable to connect: %s',
                get_class($this),
                socket_strerror(socket_last_error())
            ));
            return false;
        }

        $this->_connected = true;

        $msg = sprintf(
            "[%s] %s - %s\n",
            $level,
            DateTime::now(),
            $message
        );

        $written = socket_write($this->_socket, $msg, mb_strlen($msg));

        if (false === $written)
        {
            $this->_connected = false;
            error_log(sprintf(
                '%s - Unable to write to socket: %s',
                get_class($this),
                socket_strerror(socket_last_error())
            ));
        }

        return false;
    }
}