# PHP Consul API Status

All interactions with the [`v1/status`](https://www.consul.io/docs/agent/http/status.html) endpoint
are done via the [StatusClient](../src/Status/StatusClient.php) class.

If you have constructed a [Consul](../src/Consul.php) object, this is done as so:

```php
$status = $consul->Status;
```

## Actions

### Leader

```php
list($addrses, $err) = $consul->Status->leader();
if (null !== $err)
    die($err);

var_dump($address);
```

### Peers

```php
list($peers, $err) = $consul->Status->peers();
if (null !== $err)
    die($err);

var_dump($peers);
```