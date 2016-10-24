# php-consul-api

[![Build Status](https://travis-ci.org/dcarbone/php-consul-api.svg?branch=master)](https://travis-ci.org/dcarbone/php-consul-api)

PHP client for the [Consul HTTP API](https://www.consul.io/docs/agent/http.html)

This library is loosely based upon the [official GO client](https://github.com/hashicorp/consul/tree/master/api).

## Composer

This lib is designed to be used with [Composer](https://getcomposer.org)

Require Entry:

```json
{
    "require": {
        "dcarbone/php-consul-api": "0.4.*"    
    }
}
```

## Configuration

First, construct a [Config](./src/Config.php). This class is modeled quite closely after the
[Config Struct](https://github.com/hashicorp/consul/blob/v0.7.0/api/api.go#L104) present in the 
[Consul API Subpackage](https://github.com/hashicorp/consul/blob/v0.7.0/api).

### PSR-7 Compatibility

This lib has been designed with [PSR-7](http://www.php-fig.org/psr/psr-7/) in mind, and as a result you may use
any Http Client of your choosing, so long as it conforms to the [PSR-7](http://www.php-fig.org/psr/psr-7/) standard.

To facilitate this, this lib uses the [php-http/httpplug](https://github.com/php-http/httplug) abstraction layer.
This layer provides several different adapters for popular Http Clients (see a full list here:
[Clients](http://docs.php-http.org/en/latest/clients.html), scroll down to "Client adapters:" section).

### Default Configuration

If you have defined some of the [Consul Environment Variables](https://www.consul.io/docs/agent/options.html)
on your hosts then it would probably be easiest to simply execute the following:

```php
$config = \DCarbone\PHPConsulAPI\Config::newDefaultConfig();
```
*NOTE*: This method will attempt to locate a loaded Http Client based upon the array defined
[here](./src/Config.php#L98). 

If you are using a PSR-7 compliant Http Client that does NOT have a pre-built adapter but DOES,
that is ok!  You may use the below function to construct a configuration file with defaults and your own
Http Client instance:

```php
$myClient = my\psr7\http_client();
$config = \DCarbvone\PHPConsulAPI\Config::newDefaultConfigWithClient($myClient);
```

You will find the method definitions below:

- [Config::newDefaultConfig()](./src/Config.php#L142)
- [Config::newDefaultConfigWithClient()](./src/Config.php#L110)
 
### Advanced Configuration

You may alternatively define values yourself:

```php
$config = new \DCarbone\PHPConsulAPI\Config([
    'HttpClient' => $client // REQUIRED Instance of PSR-7 compliant HTTP client

    'Address' => 'address of server', // REQUIRED 
    'Scheme' => 'http or https', // REQUIRED
    'Datacenter' => 'name of datacenter', // OPTIONAL
    'HttpAuth' => 'user:pass', // OPTIONAL,
    'WaitTime' => 30, // OPTIONAL, not used yet
    'Token' => 'auth token', // OPTIONAL
    'TokenInHeader' => false // OPTIONAL
    'InsecureSkipVerify' => false, // OPTIONAL
]);
```

## Consul

Next, construct a [Consul](./src/Consul.php) object:

```php
$consul = new \DCarbone\PHPConsulAPI\Consul($config);
```

*NOTE*: If you do not create your own config object, [Consul](./src/Consul.php#L59) will create it's own
using [Config::newDefaultConfig()](./src/Config.php#L142).

Once constructed, you interact with each Consul API via it's corresponding Client class:

```php
list($kv_list, $qm, $err) = $consul->KV->keys();
if (null !== $err)
    die($err);

var_dump($kv_list);
```

...as an example.

## Current Clients

- [KV](./docs/KV.md)
- [Agent](./docs/AGENT.md)
- [Catalog](./docs/CATALOG.md)
- [Status](./docs/STATUS.md)
- [Event](./docs/EVENT.md)
- [Coordinate](./docs/COORDINATE.md)
- [Health](./docs/HEALTH.md)
- [Session](./docs/SESSION.md)
- [Operator](./docs/OPERATOR.md)
- [ACL](./docs/ACL.md)

More will be added as time goes on!

## Logging

Please see the [Logging](./docs/LOGGING.md) readme for more information.

## TODO

- Tests
- Parity with GO lib
- Code cleanup
