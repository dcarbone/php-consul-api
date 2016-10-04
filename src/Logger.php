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

use DCarbone\PHPConsulAPI\Logger\FileLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class Logger
 * @package DCarbone\PHPConsulAPI
 */
abstract class Logger
{
    /** @var LoggerInterface[] */
    private static $_loggers = array();

    /** @var FileLogger */
    private static $_defaultLogger = null;

    /** @var string */
    private static $_logLevel = LogLevel::WARNING;

    /** @var array */
    private static $_logLevels = array(
        LogLevel::DEBUG => 0,
        LogLevel::INFO => 1,
        LogLevel::NOTICE => 2,
        LogLevel::WARNING => 3,
        LogLevel::ERROR => 4,
        LogLevel::CRITICAL => 5,
        LogLevel::ALERT => 6,
        LogLevel::EMERGENCY => 7,
    );

    /**
     * Map to allow for older logger implementations to be used
     *
     * TODO: Remove old logging support at some point
     *
     * @var array
     */
    private static $_compatibilityLevels = array(
        LogLevel::DEBUG => 'debug',
        LogLevel::INFO => 'info',
        LogLevel::NOTICE => 'info',
        LogLevel::WARNING => 'warn',
        LogLevel::ERROR => 'error',
        LogLevel::CRITICAL => 'error',
        LogLevel::ALERT => 'error',
        LogLevel::EMERGENCY => 'error'
    );

    /**
     * Set up a file logger that outputs log data to "var/logs/php-consul-api.log"
     */
    public static function addDefaultLogger()
    {
        if (!isset(self::$_defaultLogger))
            self::$_defaultLogger = new FileLogger(__DIR__.'/../var/logs/php-consul-api.log');
    }

    /**
     * Destroy the default logger
     */
    public static function removeDefaultLogger()
    {
        self::$_defaultLogger = null;
    }

    /**
     * @param string $logLevel
     */
    public static function setLogLevel($logLevel)
    {
        self::$_logLevel = self::sanitizeLevel($logLevel);
    }

    /**
     * @param LoggerInterface[] $loggers
     * @param bool $clearDefault
     */
    public static function setLoggers(array $loggers, $clearDefault = true)
    {
        if ((bool)$clearDefault)
            self::$_defaultLogger = null;

        self::$_loggers = array();

        foreach($loggers as $logger)
        {
            self::addLogger($logger);
        }
    }

    /**
     * @param LoggerInterface $logger
     */
    public static function addLogger($logger)
    {
        if ($logger instanceof LoggerInterface)
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

    /**
     * Clear out all loggers
     */
    public static function resetLoggers()
    {
        self::$_loggers = array();
    }

    /**
     * @param string $logLevel
     * @param string $message
     * @param array $context
     */
    public static function log($logLevel, $message, array $context = array())
    {
        $logLevel = self::sanitizeLevel($logLevel);

        if (self::$_logLevels[self::$_logLevel] <= self::$_logLevels[$logLevel])
        {
            if ($message instanceof Error)
                $message = $message->getMessage();

            // Log to default logger, if set
            if (isset(self::$_defaultLogger))
                self::$_defaultLogger->{$logLevel}($message, $context);

            // Log to each user-defined logger
            foreach(self::$_loggers as $logger)
            {
                $logger->{$logLevel}($message, $context);
            }
        }
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     */
    public static function emergency($message, array $context = array())
    {
        static::log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     */
    public static function alert($message, array $context = array())
    {
        static::log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     */
    public static function critical($message, array $context = array())
    {
        static::log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     */
    public static function error($message, array $context = array())
    {
        static::log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     */
    public static function warning($message, array $context = array())
    {
        static::log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     */
    public static function notice($message, array $context = array())
    {
        static::log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     */
    public static function info($message, array $context = array())
    {
        static::log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     */
    public static function debug($message, array $context = array())
    {
        static::log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function warn($message, array $context = array())
    {
        self::log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @param string $logLevel
     * @return string
     */
    private static function sanitizeLevel($logLevel)
    {
        if (!is_string($logLevel))
        {
            throw new \InvalidArgumentException(sprintf(
                '%s - Log level must be string, %s seen.',
                get_called_class(),
                gettype($logLevel)
            ));
        }

        $level = strtolower(trim($logLevel));

        // Some backwards compatibility
        if ('warn' === $level)
            $level = LogLevel::WARNING;

        if (!isset(self::$_logLevels[$level]))
        {
            throw new \InvalidArgumentException(sprintf(
                '%s - Log level must be one of the following values: ["%s"].  %s seen.',
                get_called_class(),
                implode('", "', array_keys(self::$_logLevels)),
                is_string($logLevel) ? $logLevel : gettype($logLevel)
            ));
        }

        return $level;
    }
}