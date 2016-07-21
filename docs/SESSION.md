# PHP Consul API Session

ALl interactions with the [`v1/session`](https://www.consul.io/docs/agent/http/session.html) endpoint
are done via the [SessionClient](../src/Session/SessionClient.php) class.

If you have constructed a [Consul](../src/Consul.php) object, this is done as so:

```php
$session = $consul->Session;
```

## Actions

### Create

In order to initialize a new session, you must first create a [SessionEntry](../src/Session/SessionEntry.php) object.

Below is a quick example:

```php
$sessionEntry = new \DCarbone\PHPConsulAPI\Session\SessionEntry(
    array(
        'LockDelay' => '10s',
        'Name' => 'my-test-lock',
        'Behavior' => 'release',
        'TTL' => '15s',
    )
);

list($sid, $wm, $err) = $consul-Session->create($sessionEntry);
if (null !== $err)
    die($err);

var_dump($id, $wm);
```

### Destroy

```php
list($wm, $err) = $consul->Session->destroy($sid);
if (null !== $err)
    die($err);

var_dump($wm);
```

### Renew

```php
list($sessions, $wm, $err) = $consul->Session->renew($sid);
if (null !== $err)
    die($err);

var_dump($sessions, $wm);
```

### Info

```php
list($sessions, $qm, $err) = $consul->Session->info($sid);
if (null !== $err)
    die($err);

var_dump($seesions, $qm);
```

### List sessions belonging to a specific node

```php
list($sessions, $qm, $err) = $consul->Session->node($node);
if (null !== $err)
    die($err);

var_dump($sessions, $qm);
```

### List all sessions

```php
list($sessions, $qm, $err) = $consul->Session->listSessions();
if (null !== $err)
    die($err);

var_dump($sessions, $qm);
```