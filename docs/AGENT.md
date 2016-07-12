# PHP Consul API Agent

All interactions with the [`v1/agent`](https://www.consul.io/docs/agent/http/agent.html) endpoint are done
via the [AgentClient](../src/Agent/AgentClient.php) class.

If you have constructed a [Client](./src/Client.php) object, this is done as so:

```php
$agent = $client->Agent;
```

## Actions

### Get "Self"

```php
list($self, $qm, $err) = $client->Agent->self();
if (null !== $err)
    die($err);

var_dump($qm, $self);
```

### Get Name of Node

```php
list($nodeName, $err) = $client->Agent->nodeName();
if (null !== $err)
    die($err);

var_dump($nodeName);
```

### Get List of Agent Checks

```php
list($checks, $err) = $client->Agent->checks();
if (null !== $err)
    die($err);

var_dump($checks);
```

### Get List of Agent Services

```php
list($services, $err) = $client->Agent->services();
if (null !== $err)
    die($err);

var_dump($services);
```

### Get List of Cluster Members

```php
list($members, $err) = $client->Agent->members();
if (null !== $err)
    die($err);

var_dump($members);
```

### Register Service

To register a service, you must first create an [AgentServiceRegistration](./src/Agent/AgentServiceRegistration.php)
object.  Below is a quick and sloppy example that also creates a check:

```php
$service = new \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration(
    array(
        'Name' => 'dan test service',
        'Check' => new \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck(
            array(
                'HTTP' => 'http://127.0.0.1:8000',
                'Interval' => '10s'
            )
        )
    )
);

$err = $client->Agent->serviceRegister($service);
if (null !== $err)
    die($err);
```

### Deregister Service

```php
$err = $client->Agent->serviceDeregister('dan test service');
if (null !== $err)
    die($err);
```

### Register Agent Check

To register an agent check, you must first create an [AgentCheckRegistration](./src/Agent/AgentCheckRegistration.php)
object.  Below is a quick and sloppy example:

```php
$check = new \DCarbone\PHPConsulAPI\Agent\AgentCheckRegistration(
    array(
        'Name' => 'dan test service check',
        'TCP' => '127.0.0.1:8000',
        'Interval' => '10s',
        'ServiceID' => 'dan test service'
    )
);

$err = $client->Agent->checkRegister($check);
if (null !== $err)
    die($err);
```

### Deregister Agent Check

```php
$err = $client->Agent()->checkDeregister('dan test service check');
if (null !== $err)
    die($err);
```

### Join

```php
$err = $client->Agent->join('address to join');
if (null !== $err)
    die($err);
```

### Force Leave

```php
$err = $client->Agent->forceLeave('node name');
if (null !== $err)
    die($err);
```

### Enable Service Maintenance

```php
$err = $client->Agent->enableServiceMaintenance('service id', 'because reasons');
if (null !== $err)
    die($err);
```

### Disable Service Maintenance

```php
$err = $client->Agent->disableServiceMaintenance('service id');
if (null !== $er)
    die($err);
```

### Enable Node Maintenance

```php
$err = $client->Agent->enableNodeMaintenance('because GOOD reasons');
if (null !== $err)
    die($err);
```

### Disable Node Maintenance

```php
$err = $client->Agent->disableNodeMaintenance();
if (null !== $err)
    die($err);
```

### Set TTL Check to Passing

```php
$err = $client->Agent->passTTL('check id', 'all good here, dudes');
if (null !== $err)
    die($err);
```

### Set TTL Check to Warning

```php
$err = $client->Agent->warnTTL('check id', 'might not be good here, dudes');
if (null !== $err)
    die($err);
```

### Set TTL Check to Failing

```php
$err = $client->Agent->failTTL('check id', 'super not good here, dudes.');
if (null !== $err)
    die($err);
```
