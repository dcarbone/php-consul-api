# PHP Consul API Operator

All interactions with the [`v1/operator`](https://www.consul.io/docs/agent/http/operator.html) endpoint are done
via the [OperatorClient](../src/Operator/OperatorClient.php) class.

If you have constructed a [Consul](../src/Consul.php) object, this is done as so:

```php
$operator = $consul->Operator;
```

## Actions

### Get Raft Configuration

```php
list($raftConfiguration, $qm, $err) = $consul->Operator->getRaftConfiguration();
if (null !== $err)
    die($err);

var_dump($raftConfiguration, $qm);
```

### Remove Raft Peer with Address

```php
$err = $consul->Operator->raftRemovePeerByAddress($address);
if (null !== $err)
    die($err);
```