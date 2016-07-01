# simple-consul-php
Zero-Dependency PHP Client Library for Consul

The primary purpose of this lib is to provide a dependency-free way of interacting with [Consul](https://www.consul.io/).

It is in alpha stages of development.  Currently the only thing it can do is query KV data.

## KV

```php
$kvClient = new \DCarbone\SimpleConsulPHP\KV\KVClient('url to your consul');

/** @var string[] $keys */
$keys = $kvClient->getKeys();

/** @var \DCarbone\SimpleConsulPHP\KV\KVPair $kvp */
$kvp = $kvClient->getValue('my key');

/** @var array $tree */
$tree = $kvClient->getTree();
```