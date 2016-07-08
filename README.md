# php-consul-api

PHP client implementation for the Consul API

The primary purpose of this lib is to provide a dependency-free way of interacting with [Consul](https://www.consul.io/).

It is in alpha stages of development.  Currently the only thing it can do is query KV data.

This library is loosely based upon the [official GO client](https://github.com/hashicorp/consul/tree/master/api).

## Composer

This lib is designed to be used with [Composer](https://getcomposer.org)

Require Entry:

```json
{
  "dcarbone/php-consul-api": "dev-master"
}
```

## KV

All interactions with the `v1/kv` endpoint are done via the [KVClient](./src/KV/KVClient.php) class.

Some basic examples:

```php
use DCarbone\SimpleConsulPHP\Client;
use DCarbone\SimpleConsulPHP\KV\KVPair;

$client = new Client();

list($wm, $err) = $client->KV()->put(new KVPair(['Key' => 'prefix/mykey', 'Value' => 'my value']));

if (null !== $err)
    die('bad things happened');

list($kv, $qm, $err) = $client->KV()->get('prefix/mykey');

if (null !== $err))
    die('could not retrieve key!');

var_dump($kv);

```