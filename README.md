# php-consul-api

PHP client implementation for the Consul API

The primary purpose of this lib is to provide a dependency-free way of interacting with [Consul](https://www.consul.io/).

It is in alpha stages of development.  Currently the only thing it can do is query KV data.

This library is loosely based upon the [official GO client](https://github.com/hashicorp/consul/tree/master/api).

## TODO's

1. Create logger interface, log errors and warnings rather than just throwing exceptions everywhere
2. Implement error returning
3. Implement *AndSet actions within KV
4. Implement things other than KV

## KV

All interactions with the `v1/kv` endpoint are done via the [KVClient](./src/KV/KVClient.php) class.

Currently, only `get`, `put`, `delete`, `keys`, and `tree` actions are implemented.

Some basic examples:

```php
use DCarbone\SimpleConsulPHP\KV\KVClient;
use DCarbone\SimpleConsulPHP\ConsulConfig;
use DCarbone\SimpleConsulPHP\KV\KVPair;

$kv = new KVClient(ConsulConfig::newDefaultConfig());

$ok = $kv->put(new KVPair(['Key' => 'prefix/mykey', 'Value' => 'my value']));

if (!$ok)
    die('bad things happened');

$pair = $kv->get('prefix/mykey');

if (null === $pair)
    die('could not retrieve key!');

var_dump($pair);

```