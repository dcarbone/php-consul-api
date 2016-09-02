# PHP Consul API Logging

Logging is handled with the [Logger](../src/Logger.php) class.  To register a new logger, it must implement
the [ConsulAPILoggerInterface](../src/API/ConsulAPILoggerInterface.php) interface.

## Provided Loggers

There are a few Logger classes provided by this lib:

- [FileLogger](../src/FileLogger.php)
- [PHPLogger](../src/PHPLogger.php)
- [NullLogger](../src/NullLogger.php)
- [SocketLogger](../src/SocketLogger.php)

## Default Logger

By default, the [FileLogger](../src/FileLogger.php) is initialized and set to write to this lib's [var/log](../var/log)
directory.  You can disable the default logger by calling [Logger::removeDefaultLogger](../src/Logger.php#L63).

## Log Levels

There are 4 levels adhered to by this lib:

- debug
- info
- warn
- error

The default logging level for this lib is `warn`.

To specify a different logging level, call [Logger::setLogLevel](../src/Logger.php#L71).