# PHP Consul API Logging

Logging is handled with the [Logger](../src/Logger.php) class.  To register a new logger, it must be
[PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) compliant and implement
the [Psr\LoggerInterface](https://github.com/php-fig/log/blob/master/Psr/Log/LoggerInterface.php ) interface.

## Debug Logger

There is a single logger provided, the [FileDebugLogger](../src/FileDebugLogger.php)

To enable this logger, execute [Logger::addDebugLogger()](../src/Logger.php#L52)

## Adding Loggers

You may add a logger by calling [Logger::addLogger](../src/Logger.php#L90)
