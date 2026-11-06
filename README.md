# php-consul-api

[![Tests](https://github.com/dcarbone/php-consul-api/actions/workflows/tests.yaml/badge.svg)](https://github.com/dcarbone/php-consul-api/actions/workflows/tests.yaml)

A PHP client for the [Consul HTTP API](https://developer.hashicorp.com/consul/api-docs), modeled closely on the
official [Go client](https://github.com/hashicorp/consul/tree/main/api).

📖 **Full documentation lives in the [wiki](https://github.com/dcarbone/php-consul-api/wiki).**

## Requirements

- PHP **8.2+** with `ext-json`
- [`guzzlehttp/guzzle`](https://github.com/guzzle/guzzle) `^7.4` (default HTTP client; any `GuzzleHttp\ClientInterface` works)
- [`brick/math`](https://github.com/brick/math) `^1.0` (for `uint64` values)
- [`dcarbone/gotime`](https://github.com/dcarbone/gotime) `^v2.0` (for Go-style durations)

## Installation

```shell
composer require dcarbone/php-consul-api:^v3.1
```

## Quick Start

```php
use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use DCarbone\PHPConsulAPI\KV\KVPair;

// With no arguments, configuration is read from the standard CONSUL_* environment variables,
// falling back to http://localhost:8500.
$consul = new Consul();

// ...or configure it explicitly.
$consul = new Consul(config: new Config(
    Address: 'consul.example.com:8500',
    Token: 'my-acl-token',
));

$err = $consul->KV->Put(p: new KVPair(Key: 'app/config/db', Value: 'postgres://localhost'))->Err;
if (null !== $err) {
    throw new \RuntimeException((string)$err);
}

[$pair, $qm, $err] = $consul->KV->Get(key: 'app/config/db');
if (null !== $err) {
    throw new \RuntimeException((string)$err);
}

echo $pair?->Value;    // "postgres://localhost" ($pair is null if the key does not exist)
echo $qm->LastIndex;   // index to use for blocking queries
```

## Configuration

`Config` mirrors the Go client's `api.Config`, and `new Consul(config: $config)` applies the same defaults and
precedence as Go's `api.NewClient`. Every parameter is optional.

| Parameter            | Default / Env var                                                   | Description                                           |
|----------------------|---------------------------------------------------------------------|-------------------------------------------------------|
| `Address`            | `CONSUL_HTTP_ADDR`, else `localhost:8500`                           | `host:port`, `http://`, `https://` or `unix://` URL   |
| `Scheme`             | `CONSUL_HTTP_SSL` (true → `https`), else `http`                     | `http` or `https`                                     |
| `PathPrefix`         | `''`                                                                | Path prefix for every request (reverse proxies)       |
| `Datacenter`         | `''`                                                                | Default datacenter for requests                       |
| `Namespace`          | `CONSUL_NAMESPACE`                                                  | Default namespace (Consul Enterprise)                 |
| `Partition`          | `CONSUL_PARTITION`                                                  | Default admin partition (Consul Enterprise)           |
| `HttpAuth`           | `CONSUL_HTTP_AUTH`                                                  | HTTP basic auth, as `user:pass` or an `HttpAuth`      |
| `WaitTime`           | none                                                                | Default max wait for blocking queries (e.g. `'5m'`)   |
| `Token`              | `CONSUL_HTTP_TOKEN`                                                 | ACL token                                             |
| `TokenFile`          | `CONSUL_HTTP_TOKEN_FILE`                                            | File containing the ACL token                         |
| `CAFile`             | `CONSUL_CACERT`                                                     | CA certificate used to verify the server              |
| `CAPath`             | `CONSUL_CAPATH`                                                     | Directory of (hashed) CA certificates                 |
| `CertFile`           | `CONSUL_CLIENT_CERT`                                                | Client certificate (requires `KeyFile`)               |
| `KeyFile`            | `CONSUL_CLIENT_KEY`                                                 | Client key (requires `CertFile`)                      |
| `TLSServerName`      | `CONSUL_TLS_SERVER_NAME`                                            | SNI server name (stored only, see wiki)               |
| `InsecureSkipVerify` | `CONSUL_HTTP_SSL_VERIFY` (false → skip), else `false`               | Disable TLS certificate verification                  |
| `HttpClient`         | `new GuzzleHttp\Client()`                                           | Any `GuzzleHttp\ClientInterface`                      |
| `JSONEncodeOpts`     | `JSON_UNESCAPED_SLASHES`                                            | Flags passed to `json_encode`                         |
| `JSONDecodeMaxDepth` | `512`                                                               | Max depth passed to `json_decode`                     |
| `JSONDecodeOpts`     | `0`                                                                 | Flags passed to `json_decode`                         |

Boolean env vars are parsed like Go's `strconv.ParseBool` (`1`, `t`, `T`, `TRUE`, `true`, `True` and their false
equivalents); empty env vars are treated as unset.

For proxies, custom timeouts, middleware, etc., construct your own Guzzle client and pass it as `HttpClient`.
See [Configuration](https://github.com/dcarbone/php-consul-api/wiki/Configuration) for address parsing, token
precedence, TLS and other details.

## Working with Responses

Every API call returns a response object. Responses can be destructured like the multiple return values of the Go
client, or used via properties and getters:

```php
// Read endpoints: [value, QueryMeta, error]
[$services, $qm, $err] = $consul->Catalog->Services();

// Write endpoints with a value: [value, WriteMeta, error]
[$sessionID, $wm, $err] = $consul->Session->Create(sessionEntry: $entry);

// Write endpoints without a value: [WriteMeta, error]
[$wm, $err] = $consul->KV->Delete(key: 'app/config/db');

// Some endpoints return no metadata: [value, error]
[$leader, $err] = $consul->Status->Leader();

// Object form
$resp = $consul->KV->Get(key: 'app/config/db');
$resp->getValue(); $resp->getQueryMeta(); $resp->getErr();
```

A few Agent methods return only `null|Error`. The exact shape of each method is documented on its client's
[wiki page](https://github.com/dcarbone/php-consul-api/wiki/Responses-and-Errors).

Errors are never thrown for transport or API failures. They are returned as `DCarbone\PHPConsulAPI\PHPLib\Error`
instances, which can be cast to a string.

### Query and write options

Each method accepts an optional `QueryOptions` or `WriteOptions` argument to override the datacenter, token, consistency
mode, and so on per request. Blocking queries work the same as in Go:

```php
use DCarbone\PHPConsulAPI\QueryOptions;

[$pair, $qm, $err] = $consul->KV->Get(key: 'app/config/db');
[$pair, $qm, $err] = $consul->KV->Get(
    key: 'app/config/db',
    opts: new QueryOptions(WaitIndex: $qm->LastIndex, WaitTime: '30s'),
);
```

## Big Integers (uint64)

As of **v3.1.0**, every field that Consul defines as `uint64` is a [`Brick\Math\BigInteger`](https://github.com/brick/math).
This covers `CreateIndex`, `ModifyIndex`, `LockIndex`, `KVPair::$Flags`, `QueryMeta::$LastIndex`,
`QueryOptions::$WaitIndex`, Raft indexes and terms, and the return value of `EventClient::IDToIndex()`.
PHP's `int` is signed, so values above `PHP_INT_MAX` would otherwise overflow.

- **Setting:** constructors and setters accept a non-negative `int`, a numeric `string`, or a `BigInteger`. Strings are
  validated with `BigInteger::parse()`. Negative, non-integer, and out-of-range values throw `\InvalidArgumentException`.
- **Reading:** use the `BigInteger` API (`isEqualTo()`, `toInt()`, `(string)`) rather than PHP `int` operators.
- **JSON:** responses are decoded with `JSON_BIGINT_AS_STRING`. Use `\DCarbone\PHPConsulAPI\PHPLib\encode_json()`
  rather than `json_encode()` if you need `BigInteger` values written as JSON numbers.

```php
$pair = new KVPair(Key: 'app/config', Flags: '18446744073709551615');

if ($pair->Flags->isEqualTo(42)) { /* ... */ }
$index = (string)$qm->LastIndex;
```

See [Big Integers](https://github.com/dcarbone/php-consul-api/wiki/Big-Integers).

## Clients

Each client is available on `Consul` both as a property (`$consul->KV`) and as a method (`$consul->KV()`).

| Client          | Consul API                                                                     | Wiki                                                                           |
|-----------------|--------------------------------------------------------------------------------|--------------------------------------------------------------------------------|
| `ACL`           | [`/v1/acl`](https://developer.hashicorp.com/consul/api-docs/acl)               | [ACL](https://github.com/dcarbone/php-consul-api/wiki/ACL)                     |
| `Agent`         | [`/v1/agent`](https://developer.hashicorp.com/consul/api-docs/agent)           | [Agent](https://github.com/dcarbone/php-consul-api/wiki/Agent)                 |
| `Catalog`       | [`/v1/catalog`](https://developer.hashicorp.com/consul/api-docs/catalog)       | [Catalog](https://github.com/dcarbone/php-consul-api/wiki/Catalog)             |
| `ConfigEntry`   | [`/v1/config`](https://developer.hashicorp.com/consul/api-docs/config)         | [ConfigEntry](https://github.com/dcarbone/php-consul-api/wiki/ConfigEntry)     |
| `Coordinate`    | [`/v1/coordinate`](https://developer.hashicorp.com/consul/api-docs/coordinate) | [Coordinate](https://github.com/dcarbone/php-consul-api/wiki/Coordinate)       |
| `Debug`         | `/debug/pprof`                                                                 | [Debug](https://github.com/dcarbone/php-consul-api/wiki/Debug)                 |
| `Event`         | [`/v1/event`](https://developer.hashicorp.com/consul/api-docs/event)           | [Event](https://github.com/dcarbone/php-consul-api/wiki/Event)                 |
| `Health`        | [`/v1/health`](https://developer.hashicorp.com/consul/api-docs/health)         | [Health](https://github.com/dcarbone/php-consul-api/wiki/Health)               |
| `KV`            | [`/v1/kv`](https://developer.hashicorp.com/consul/api-docs/kv)                 | [KV](https://github.com/dcarbone/php-consul-api/wiki/KV)                       |
| `Operator`      | [`/v1/operator`](https://developer.hashicorp.com/consul/api-docs/operator)     | [Operator](https://github.com/dcarbone/php-consul-api/wiki/Operator)           |
| `Peering`       | [`/v1/peering`](https://developer.hashicorp.com/consul/api-docs/peering)       | [Peering](https://github.com/dcarbone/php-consul-api/wiki/Peering)             |
| `PreparedQuery` | [`/v1/query`](https://developer.hashicorp.com/consul/api-docs/query)           | [PreparedQuery](https://github.com/dcarbone/php-consul-api/wiki/PreparedQuery) |
| `Session`       | [`/v1/session`](https://developer.hashicorp.com/consul/api-docs/session)       | [Session](https://github.com/dcarbone/php-consul-api/wiki/Session)             |
| `Status`        | [`/v1/status`](https://developer.hashicorp.com/consul/api-docs/status)         | [Status](https://github.com/dcarbone/php-consul-api/wiki/Status)               |
| `Txn`           | [`/v1/txn`](https://developer.hashicorp.com/consul/api-docs/txn)               | [Txn](https://github.com/dcarbone/php-consul-api/wiki/Txn)                     |

Unlike the Go client, there are no separate `*Opts` method variants. Each method takes an optional trailing options
argument instead.

## Models

Models are plain PHP classes with public properties, getters and setters, and named constructor parameters:

```php
use DCarbone\PHPConsulAPI\Agent\AgentServiceCheck;
use DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration;

$err = $consul->Agent->ServiceRegister(service: new AgentServiceRegistration(
    ID: 'web-1',
    Name: 'web',
    Tags: ['v1'],
    Port: 8080,
    Check: new AgentServiceCheck(TTL: '30s', DeregisterCriticalServiceAfter: '1m'),
));
```

Duration fields accept Go duration strings (`'30s'`), integers (nanoseconds), `\DateInterval`, or
`DCarbone\Go\Time\Duration`. They are stored as `Time\Duration`.

Constructing models from an associative array (`new KVPair(data: [...])`) is deprecated. Use named parameters, or
`Model::jsonUnserialize($decoded)` to hydrate from decoded JSON.

## Version Compatibility

`v3.x` requires PHP 8.2+.

| PHPConsulAPI | Consul      |
|--------------|-------------|
| 0.3.x        | 0.6.4       |
| 0.6.x        | 0.7–0.8     |
| v1.x         | 0.9–current |
| v2.x         | 0.9–current |
| v3.x         | 0.9–current |
| dev-main     | current     |

The library is tested against the latest Consul release. Newer versions may work with older Consul agents in a limited
capacity, but backwards-compatibility issues with older agents will not be addressed.

### Upgrading to v3

1. `FakeMap`, `FakeSlice`, `ReadableDuration` and the `Transcoding` helpers have been removed.
   Map-like fields are now associative arrays.
2. All models, including `Config`, use named constructor parameters. Array construction of models is deprecated, and
   `Config` can no longer be constructed from an array.
3. **(v3.1.0)** `uint64` fields are `Brick\Math\BigInteger`. See [Big Integers (uint64)](#big-integers-uint64).
4. **(v3.1.0)** `dcarbone/gotime` has been upgraded to `v2`.
5. **(next release)** TLS certificates are now verified by default, `CAFile` is honored, and
   `CONSUL_HTTP_SSL_VERIFY=false` disables verification, matching the Go client. Previously, verification was
   disabled unless `InsecureSkipVerify` was `true`.
6. **(next release)** `HttpAuth` / `CONSUL_HTTP_AUTH` credentials are now sent as HTTP basic auth on every request.
   Previously they were parsed but never sent.
7. **(next release)** `Config` now follows Go's `api.NewClient` exactly: the default address is `localhost:8500`,
   `Address` may carry a `http://`, `https://` or `unix://` scheme and a path prefix, `TokenFile` follows Go's token
   precedence, boolean env vars use Go `ParseBool` semantics (`yes`/`no`/`on`/`off` are no longer accepted), and
   `Partition`, `PathPrefix`, `CAPath` and `TLSServerName` were added.

See [Upgrading](https://github.com/dcarbone/php-consul-api/wiki/Upgrading) for details.

## Development

```shell
composer install

# unit tests only
./vendor/bin/phpunit -c phpunit.xml --testsuite unit

# full suite; integration tests start and stop a local `consul` agent automatically,
# so the `consul` binary must be on your PATH
./vendor/bin/phpunit -c phpunit.xml

# static analysis and refactoring tools are installed separately under tools/
composer install --working-dir tools/rector
composer install --working-dir tools/phpstan
composer install --working-dir tools/psalm
composer rector
composer phpstan
composer psalm
```

See [Development](https://github.com/dcarbone/php-consul-api/wiki/Development).

## License

[Apache-2.0](./LICENSE)
