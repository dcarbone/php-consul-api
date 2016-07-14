# php-consul-api

PHP client implementation for the [Consul API](https://www.consul.io/docs/agent/http.html)

This library is loosely based upon the [official GO client](https://github.com/hashicorp/consul/tree/master/api).

## Composer

This lib is designed to be used with [Composer](https://getcomposer.org)

Require Entry:

```json
{
  "dcarbone/php-consul-api": "0.2.*"
}
```

## Usage

First, construct a [Config](./src/Config.php) object:

```php
$config = new \DCarbone\PHPConsulAPI\Config(['Address' => 'address of your consul agent']);
```

Next, construct a [Consul](./src/Consul.php) object:

```php
$consul = new \DCarbone\PHPConsulAPI\Consul($config);
```

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

More will be added as time goes on!


## TODO

- Tests
- Parity with GO lib
- Code cleanup