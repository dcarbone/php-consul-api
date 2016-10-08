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

use Psr\Log\AbstractLogger;

/**
 * Class FileDebugLogger
 * @package DCarbone\PHPConsulAPI
 */
class FileDebugLogger extends AbstractLogger
{
    /** @var string */
    private $_file = '';

    /** @var bool */
    private $_exceptionOnError;

    /**
     * FileLogger constructor.
     * @param bool $exceptionOnError
     */
    public function __construct($exceptionOnError = true)
    {
        $this->_exceptionOnError = (bool)$exceptionOnError;

        $file = __DIR__ . '/../var/logs/php-consul-api.log';

        if (false === @file_exists($file) && false === (bool)@file_put_contents($file, "\n"))
        {
            throw new \InvalidArgumentException(sprintf(
                'FileLogger - Unable to create file at path "%s"',
                $file
            ));
        }

        $this->_file = $file;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @throws \Exception
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        if (file_exists($this->_file) && is_writable($this->_file))
        {
            // Try to write out line
            file_put_contents(
                $this->_file,
                sprintf(
                    "[%s] %s - %s\n",
                    $level,
                    DateTime::now(),
                    $message
                ),
                FILE_APPEND
            );
        }
        else
        {
            $msg = sprintf(
                'FileLogger - Specified file "%s" could not be opened for writing.',
                $this->_file
            );

            if ($this->_exceptionOnError)
                throw new \Exception($msg);

            trigger_error($msg, E_USER_ERROR);
        }

        return null;
    }
}