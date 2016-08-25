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
 * Class Logger
 * @package DCarbone\PHPConsulAPI
 */
abstract class Logger
{
    /** @var ConsulAPILoggerInterface[] */
    private static $_loggers = array();

    /** @var FileLogger */
    private static $_defaultLogger = null;

    /** @var bool */
    private static $_initialized = false;

    /** @var string */
    private static $_logLevel = 'warn';

    /** @var array */
    private static $_logLevels = array(
        'debug' => 0,
        'info' => 1,
        'warn' => 2,
        'error' => 3,
    );

    public static function init()
    {
        if (self::$_initialized)
            return;

        self::$_defaultLogger = new FileLogger(__DIR__.'/../var/logs/php-consul-api.log');

        self::$_initialized = true;
    }

    public static function removeDefaultLogger()
    {
        self::$_defaultLogger = null;
    }

    /**
     * @param string $logLevel
     */
    public static function setLogLevel($logLevel)
    {
        if (!is_string($logLevel) || '' === ($level = strtolower($logLevel)) || !isset(self::$_logLevels[$level]))
        {
            throw new \InvalidArgumentException(sprintf(
                '%s - Log level must be one of the following values: ["%s"].  %s seen.',
                get_called_class(),
                implode('", "', array_keys(self::$_logLevels)),
                is_string($logLevel) ? $logLevel : gettype($logLevel)
            ));
        }

        self::$_logLevel = $level;
    }

    /**
     * @param ConsulAPILoggerInterface[] $loggers
     */
    public static function setLoggers(array $loggers)
    {
        self::$_loggers = array();
        self::$_defaultLogger = null;
        foreach($loggers as $logger)
        {
            if ($logger instanceof ConsulAPILoggerInterface)
            {
                self::$_loggers[] = $logger;
            }
            else
            {
                throw new \InvalidArgumentException(sprintf(
                    '%s - %s is not a valid logger implementation',
                    get_called_class(),
                    is_object($logger) ? get_class($logger) : gettype($logger)
                ));
            }
        }
    }

    /**
     * @param ConsulAPILoggerInterface $logger
     */
    public static function addLogger(ConsulAPILoggerInterface $logger)
    {
        self::$_loggers[] = $logger;
    }

    /**
     * Clear out all loggers
     */
    public static function resetLoggers()
    {
        self::$_loggers = array();
    }

    /**
     * @param string $level
     * @param string $message
     */
    public static function log($level, $message)
    {
        if (!is_string($level) || !isset(self::$_logLevels[$level]))
        {
            trigger_error(sprintf(
                '%s::log - Specified level "%s" is not valid.  Available levels are: ["%s"]',
                get_called_class(),
                is_string($level) ? $level : gettype($level),
                implode('","', array_keys(self::$_logLevels))
            ));
            return;
        }

        if (self::$_logLevels[self::$_logLevel] <= self::$_logLevels[$level])
        {
            if ($message instanceof Error)
                $message = $message->getMessage();

            if (self::$_defaultLogger)
                self::$_defaultLogger->{$level}($message);

            foreach(self::$_loggers as $logger)
            {
                $logger->{$level}($message);
            }
        }
    }

    /**
     * @param string $message
     */
    public static function error($message)
    {
        self::log('error', $message);
    }

    /**
     * @param string $message
     */
    public static function warn($message)
    {
        self::log('warn', $message);
    }

    /**
     * @param string $message
     */
    public static function info($message)
    {
        self::log('info', $message);
    }

    /**
     * @param string $message
     */
    public static function debug($message)
    {
        self::log('debug', $message);
    }
}
Logger::init();