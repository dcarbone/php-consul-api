# php-consul-api

[![Build Status](https://travis-ci.org/dcarbone/php-consul-api.svg?branch=master)](https://travis-ci.org/dcarbone/php-consul-api)

PHP client for the [Consul HTTP API](https://www.consul.io/docs/agent/http.html)

This library is loosely based upon the [official GO client](https://github.com/hashicorp/consul/tree/master/api).

## Version Compatibility

|PHPConsulAPI Version|Consul Version|
|---|---|
|0.3.x|0.6.4|
|0.6.x|0.7-0.8|
|0.7.x|0.8-0.9|

Newer versions of the api lib will probably work in a limited capacity with older versions of Consul, but no guarantee 
is made and backwards compatibility issues will not be addressed.

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
[Config Struct](https://github.com/hashicorp/consul/blob/v0.9.3/api/api.go#L202) present in the
[Consul API Subpackage](https://github.com/hashicorp/consul/blob/v0.9.3/api).

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
    'HttpClient' => $client,            // [required] Client conforming to GuzzleHttp\ClientInterface
    'Address' => 'address of server',   // [required]
    
    'Scheme' => 'http or https',            // [optional] defaults to "http"
    'Datacenter' => 'name of datacenter',   // [optional]
    'HttpAuth' => 'user:pass',              // [optional]
    'Token' => 'auth token',                // [optional] default auth token to use
    'TokenInHeader' => false,               // [optional] specifies whether to send the token in the header or query string
    'InsecureSkipVerify' => false,          // [optional] if set to true, ignores all SSL validation
    'CAFile' => '',                         // [optional] path to ca cert file, see http://docs.guzzlephp.org/en/latest/request-options.html#verify
    'CertFile' => '',                       // [optional] path to client public key.  if set, requires KeyFile also be set
    'KeyFile' => '',                        // [optional] path to client private key.  if set, requires CertFile also be set
]);
```

#### Configuration Note:

By default, this client will attempt to locate a series of environment variables to describe much of the above
configuration properties.  See [here](./src/Config.php#L450) for that list, and see [here](./src/Consul.php#L36) for
a list of the env var names.

For more advanced client configuration, such as proxy configuration, you must construct your own GuzzleHttp client
prior to constructing a PHPConsulAPI Config object.

As an example:

```php
$proxyClient = new \GuzzleHttp\Client(['proxy' => 'whatever proxy you want']]);
$config = new \DCarbone\PHPConsulAPI\Config([
    'HttpClient' => $proxyClient,             
    'Address' => 'address of server',
]);
```

When constructing your client, if you are using the `GuzzleHttp\Client` object directly or derivative thereof, you may
pass any option listed in the [Guzzle Request Options](http://docs.guzzlephp.org/en/latest/request-options.html).

## Consul

Next, construct a [Consul](./src/Consul.php) object:

```php
$consul = new \DCarbone\PHPConsulAPI\Consul($config);
```

*NOTE*: If you do not create your own config object, [Consul](./src/Consul.php#L78) will create it's own
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

- [ACL](./src/ACL/ACLClient.php)
- [Agent](./src/Agent/AgentClient.php)
- [Catalog](./src/Catalog/CatalogClient.php)
- [Coordinate](./src/Coordinate/CoordinateClient.php)
- [Event](./src/Event/EventClient.php)
- [Health](./src/Health/HealthClient.php)
- [KV](./src/KV/KVClient.php)
- [Operator](./src/Operator/OperatorClient.php)
- [Session](./src/Session/SessionClient.php)
- [Status](./src/Status/StatusClient.php)

More will be added as time goes on!

## Tests

The testing suite is still in it's infancy, however it is being tested directly against an actual Consul agent.
They will be back-filled as time allows.  Future plans are to set up a simple cluster to provide a more real-world
testing scenario.
