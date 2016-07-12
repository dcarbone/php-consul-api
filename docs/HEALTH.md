# PHP Consul API Health

ALl interactions with the [`v1/health`](https://www.consul.io/docs/agent/http/health.html) endpoint are done
via the [HealthClient](./src/Health/HealthClient.php) class.

If you have constructed a [Client](./src/Client.php) object, this is done as so:

```php
$health = $client->Health;
```

## Actions

### Get Node Health

```php
list($healthCheck, $qm, $err) = $client->Health->node('nodename');
if (null !== $err)
    die($err);

var_dump($healthCheck, $qm);
```

### Get Service Checks

```php
list($checks, $qm, $err) = $client->Health->checks('service name');
if (null !== $err)
    die($err);

var_dump($checks, $qm);
```

### Get Service Health

```php
list($services, $qm, $err) = $client->Health->service('service name');
if (null !== $err)
    die($err);

var_dump($services, $qm);
```

### Get Checks in State

```php
list($checks, $qm, $err) = $client->health->state('state');
if (null !== $err)
    die($err);

var_dump($checks, $qm);
```