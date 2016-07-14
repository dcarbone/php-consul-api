# PHP Consul API Event

All interactions with the [`v1/event`](https://www.consul.io/docs/agent/http/event.html) endpoint
are done via the [EventClient](../src/Event/EventClient.php) class.

If you have constructed a [Consul](../src/Consul.php) object, this is done as so:

```php
$event = $consul->Event;
```

## Actions

### Fire an Event

In order to fire an event, you must first create an [UserEvent](../src/Event/UserEvent.php)
object.

Below is a quick example:

```php
$userEvent = new \DCarbone\PHPConsulAPI\Event\UserEvent(
    array(
        'Name' => 'sandwiches',
        'Payload' => 'They are tasty.'
    )
);

list($event, $wm, $err) = $consul->Event->fire($userEvent);
if (null !== $err)
    die($err);

var_dump($event, $wm);
```

### List Events

```php
list($userEvents, $qm, $err) = $consul->Event->eventList();
if (null !== $err)
    die($err);

var_dump($userEvents, $qm);
```