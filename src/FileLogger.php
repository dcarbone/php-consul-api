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
 * Class FileLogger
 * @package DCarbone\PHPConsulAPI
 */
class FileLogger implements ConsulAPILoggerInterface
{
    /** @var string */
    private $_file;

    /** @var bool */
    private $_exceptionOnError;

    /**
     * FileLogger constructor.
     * @param $file
     * @param bool $exceptionOnError
     */
    public function __construct($file, $exceptionOnError = false)
    {
        $this->_exceptionOnError = (bool)$exceptionOnError;

        if (is_string($file))
        {
            $this->_file = $file;

            if (false === @file_exists($file) && false === $this->_tryCreateFile())
            {
                throw new \InvalidArgumentException(sprintf(
                    'FileLogger - Unable to create file at path "%s"',
                    $file
                ));
            }
        }
        else
        {
            throw new \InvalidArgumentException(sprintf(
                'FileLogger - Constructor expects argument to be string containing path to file, %s seen.',
                gettype($file)
            ));
        }
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
        if ($this->_canLog())
        {
            // Try to write out line
            $ok = (bool)@file_put_contents(
                $this->_file,
                sprintf(
                    "[%s] %s - %s\n",
                    $level,
                    DateTime::now(),
                    $message
                ),
                FILE_APPEND | LOCK_EX
            );

            if ($ok)
                return true;
        }

        $msg = sprintf(
            'FileLogger - Specified file "%s" could not be opened for writing.',
            $this->_file
        );

        if ($this->_exceptionOnError)
            throw new \Exception($msg);

        trigger_error($msg, E_USER_ERROR);

        return false;
    }

    /**
     * @return bool
     */
    private function _canLog()
    {
        return file_exists($this->_file) && is_writable($this->_file);
    }

    /**
     * @return bool
     */
    private function _tryCreateFile()
    {
        return (bool)@file_put_contents($this->_file, "\n");
    }
}