# PHP Consul API Logging

Logging is handled with the [Logger](../src/Logger.php) class.  To register a new logger, it must be
[PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) compliant and implement
the [Psr\LoggerInterface](https://github.com/php-fig/log/blob/master/Psr/Log/LoggerInterface.php ) interface.

## Provided Loggers

There are a few Logger classes provided by this lib:

- [FileLogger](../src/FileLogger.php)
- [PHPLogger](../src/PHPLogger.php)
- [SocketLogger](../src/SocketLogger.php)

## Default Logger

For quick and lazy debugging or the like, you may optionally call [Logger::addDefaultLogger](../src/Logger.php#L73).
This creates a new [FileLogger](../src/FileLogger.php) that writes to `var/logs/php-consul-api.log`.

## Adding Loggers

You may add a logger by calling [Logger::addLogger](../src/Logger.php#L112)

## 