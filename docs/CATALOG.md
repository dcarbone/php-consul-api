# PHP Consul API Catalog

*This class is still under heavy development*

All interactions with the [`v1/catalog`](https://www.consul.io/docs/agent/http/catalog.html) endpoint are done
via the [CatalogClient](./src/Catalog/CatalogClient.php) class.

If you have constructed a [Client](./src/Client.php) object, this is done as so:

```php
$catalog = $client->Catalog();
```

## Actions

### Register Node, Service, or Check

For this endpoint, you must first create a [CatalogRegistration](./src/Catalog/CatalogRegistration.php)
object.  Below is a quick example on how to do this with a Service:

```php
$catalogRegistration = new \DCarbone\PHPConsulAPI\Catalog\CatalogRegistration(
    array(
        'Node' => 'name of node',
        'Address' => 'address of node',
        'Service' => new \DCarbone\PHPConsulAPI\Agent\AgentService(
            array(
                'Service' => 'dan-no-space-test',
            )
        )
    )
);

list($wm, $err) = $client->Catalog()->register($catalogRegistration);
if (null !== $err)
    die($err);

var_dump($wm);
```

### Deregister Node, Service, or Check

For this endpoint, you must first create a [CatalogDeregistration](./src/Catalog/CatalogDeregistration.php)
object.  Below is an example of how to deregister the service created above:

```php

$catalogDeregistration = new \DCarbone\PHPConsulAPI\Catalog\CatalogDeregistration(
    array(
        'Node' => 'name of node',
        'Address' => 'address of node',
        'ServiceID' => 'dan-no-space-test'
    )
);

list($wm, $err) = $client->Catalog()->deregister($catalogDeregistration);
if (null !== $err)
    die($err);

var_dump($wm);
```

### List Datacenters

```php
list ($datacenters, $err) = $client->Catalog()->datacenters();
if (null !== $err)
    die($err);

var_dump($datacenters);
```

### List Nodes

```php
list($nodes, $qm, $err) = $client->Catalog()->nodes();
if (null !== $err)
    die($err);

var_dump($nodes, $qm);
```

### List Services

```php
list($services, $qm, $err) = $client->Catalog()->services();
if (null !== $err)
    die($err);

var_dump($services, $qm);
```

### Get Specific Service

```php
list($service, $qm, $err) = $client->Catalog()->service('servicename');
if (null !== $err)
    die($err);

var_dump($service, $qm);
```

### Get Specific Node

```php
list($node, $qm, $err) = $client->Catalog()->node('nodename');
if (null !== $err)
    die($err);

var_dump($err);
```