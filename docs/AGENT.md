# PHP Consul API Agent

All interactions with the [`v1/agent`](https://www.consul.io/docs/agent/http/agent.html) endpoint are done
via the [AgentClient](../src/Agent/AgentClient.php) class.

If you have constructed a [Consul](../src/Consul.php) object, this is done as so:

```php
$agent = $consul->Agent;
```

## Actions

### Get "Self"

```php
list($self, $qm, $err) = $consul->Agent->self();
if (null !== $err)
    die($err);

var_dump($qm, $self);
```

### Get Name of Node

```php
list($nodeName, $err) = $consul->Agent->nodeName();
if (null !== $err)
    die($err);

var_dump($nodeName);
```

### Get List of Agent Checks

```php
list($checks, $err) = $consul->Agent->checks();
if (null !== $err)
    die($err);

var_dump($checks);
```

### Get List of Agent Services

```php
list($services, $err) = $consul->Agent->services();
if (null !== $err)
    die($err);

var_dump($services);
```

### Get List of Cluster Members

```php
list($members, $err) = $consul->Agent->members();
if (null !== $err)
    die($err);

var_dump($members);
```

### Register Service

To register a service, you must first create an [AgentServiceRegistration](../src/Agent/AgentServiceRegistration.php)
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

$err = $consul->Agent->serviceRegister($service);
if (null !== $err)
    die($err);
```

### Deregister Service

```php
$err = $consul->Agent->serviceDeregister('dan test service');
if (null !== $err)
    die($err);
```

### Register Agent Check

To register an agent check, you must first create an [AgentCheckRegistration](../src/Agent/AgentCheckRegistration.php)
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

$err = $consul->Agent->checkRegister($check);
if (null !== $err)
    die($err);
```

### Deregister Agent Check

```php
$err = $consul->Agent()->checkDeregister('dan test service check');
if (null !== $err)
    die($err);
```

### Join

```php
$err = $consul->Agent->join('address to join');
if (null !== $err)
    die($err);
```

### Force Leave

```php
$err = $consul->Agent->forceLeave('node name');
if (null !== $err)
    die($err);
```

### Enable Service Maintenance

```php
$err = $consul->Agent->enableServiceMaintenance('service id', 'because reasons');
if (null !== $err)
    die($err);
```

### Disable Service Maintenance

```php
$err = $consul->Agent->disableServiceMaintenance('service id');
if (null !== $er)
    die($err);
```

### Enable Node Maintenance

```php
$err = $consul->Agent->enableNodeMaintenance('because GOOD reasons');
if (null !== $err)
    die($err);
```

### Disable Node Maintenance

```php
$err = $consul->Agent->disableNodeMaintenance();
if (null !== $err)
    die($err);
```

### Set TTL Check to Passing

```php
$err = $consul->Agent->passTTL('check id', 'all good here, dudes');
if (null !== $err)
    die($err);
```

### Set TTL Check to Warning

```php
$err = $consul->Agent->warnTTL('check id', 'might not be good here, dudes');
if (null !== $err)
    die($err);
```

### Set TTL Check to Failing

```php
$err = $consul->Agent->failTTL('check id', 'super not good here, dudes.');
if (null !== $err)
    die($err);
```
