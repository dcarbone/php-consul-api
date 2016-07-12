# PHP Consul API Coordinate

All interactions with the [`v1/coordinate`](https://www.consul.io/docs/agent/http/coordinate.html) endpoint are done
via the [CoordinateClient](./src/Coordinate/CoordinateClient.php) class.

If you have constructed a [Client](./src/Client.php) object, this is done as so:

```php
$coordinate = $client->Coordinate;
```

## Actions

### Get Datacenter Map

```php
list($datacenters, $err) = $client->Coordinate->datacenters();
if (null !== $err)
    die($err);

var_dump($datacenters);
```

### Get Nodes List

```php
list($nodes, $qm, $err) = $client->Coordinate->nodes();
if (null !== $err)
    die($err);

var_dupm($nodes, $qm);
```