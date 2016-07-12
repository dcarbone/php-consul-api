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
 * Class PHPLogger
 * @package DCarbone\PHPConsulAPI
 */
class PHPLogger implements ConsulAPILoggerInterface
{
    /** @var int */
    private $_mode;

    /** @var string */
    private $_destination;

    /** @var string */
    private $_extraHeaders;

    /** @var bool */
    private $_exceptionOnError;

    /**
     * PHPLogger constructor.
     * @param int $mode
     * @param string $destination
     * @param string $extraHeaders
     * @param bool $exceptionOnError
     */
    public function __construct($mode = 0, $destination = null, $extraHeaders = null, $exceptionOnError = false)
    {
        if (is_int($mode) && 0 <= $mode && 4 >= $mode && 2 !== $mode)
        {
            $this->_mode = $mode;
        }
        else
        {
            throw new \InvalidArgumentException(sprintf(
                'PHPLogger - "$mode" must be 0, 1, 3, or 4. %s seen. See http://php.net/manual/en/function.error-log.php for more information',
                is_int($mode) ? $mode : gettype($mode)
            ));
        }

        if (1 === $mode)
        {
            if (is_string($destination) && filter_var($destination, FILTER_VALIDATE_EMAIL))
            {
                $this->_destination = $destination;
            }
            else
            {
                throw new \InvalidArgumentException(sprintf(
                    'PHPLogger - $destination must be a string containing a valid email address, %s seen',
                    is_string($destination) ? $destination : gettype($destination)
                ));
            }

            if (null === $extraHeaders || is_string($extraHeaders))
            {
                $this->_extraHeaders = $extraHeaders;
            }
            else
            {
                throw new \InvalidArgumentException(sprintf(
                    'PHPLogger - $extraHeaders must either be null or a string, %s seen.',
                    gettype($extraHeaders)
                ));
            }
        }

        $this->_exceptionOnError = (bool)$exceptionOnError;
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
     * @return bool
     * @throws \Exception
     */
    private function _log($level, $message)
    {
        $message = sprintf(
            "[%s] %s - %s\n",
            $level,
            DateTime::now(),
            $message
        );

        if (0 === $this->_mode || 3 === $this->_mode || 4 === $this->_mode)
        {
            $ok = @error_log($message);
        }
        else if (1 === $this->_mode)
        {
            $ok = @error_log($message, $this->_mode, $this->_destination, $this->_extraHeaders);
        }
        else
        {
            throw new \Exception(sprintf(
                'PHPLogger - Invalid state seen, $mode is not set to a valid value! Current mode: "%s"',
                is_int($this->_mode) ? $this->_mode : gettype($this->_mode)
            ));
        }
        
        if ($ok)
            return true;

        $msg = sprintf(
            'PHPLogger - Unable to log message "%s"',
            $message
        );

        if ($this->_exceptionOnError)
            throw new \Exception($msg);

        trigger_error($msg, E_USER_ERROR);

        return false;
    }
}