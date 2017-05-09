# php-consul-api

[![Build Status](https://travis-ci.org/dcarbone/php-consul-api.svg?branch=master)](https://travis-ci.org/dcarbone/php-consul-api)

PHP client for the [Consul HTTP API](https://www.consul.io/docs/agent/http.html)

This library is loosely based upon the [official GO client](https://github.com/hashicorp/consul/tree/master/api).

## Version Compatibility

|PHPConsulAPI Version|Consul Version|
|---|---|
|0.3.x|0.6.4|
|0.5.x|0.7-0.8|

## Composer

This lib is designed to be used with [Composer](https://getcomposer.org)

Require Entry:

```json
{
    "require": {
        "dcarbone/php-consul-api": "@stable"
    }
}
```

## Configuration

First, construct a [Config](./src/Config.php). This class is modeled quite closely after the
[Config Struct](https://github.com/hashicorp/consul/blob/v0.7.0/api/api.go#L104) present in the
[Consul API Subpackage](https://github.com/hashicorp/consul/blob/v0.7.0/api).

### Default Configuration

If you have defined some of the [Consul Environment Variables](https://www.consul.io/docs/agent/options.html)
on your hosts then it would probably be easiest to simply execute the following:

```php
$config = \DCarbone\PHPConsulAPI\Config::newDefaultConfig();
```

### Advanced Configuration

You may alternatively define values yourself:

```php
$config = new \DCarbone\PHPConsulAPI\Config([
    'HttpClient' => $client // REQUIRED Client conforming to GuzzleHttp\ClientInterface

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

*NOTE*: If you do not create your own config object, [Consul](./src/Consul.php#L75) will create it's own
using [Config::newDefaultConfig()](./src/Config.php#L147) and attempt to locate a suitable HTTP Client.

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

## Tests

The testing suite is still in it's infancy, however it is being tested directly against an actual Consul agent.
They will be back-filled as time allows.  Future plans are to set up a simple cluster to provide a more real-world
testing scenario.
