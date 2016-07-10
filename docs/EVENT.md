# PHP Consul API Event

All interactions with the [`v1/event`](https://www.consul.io/docs/agent/http/event.html) endpoint
are done via the [EventClient](./src/Event/EventClient.php) class.

If you have constructed a [Client](./src/Client.php) object, this is done as so:

```php
$event = $client->Event();
```

## Actions

### Fire an Event

In order to fire an event, you must first create an [UserEvent](./src/Event/UserEvent.php)
object.

Below is a quick example:

```php
$event = new
```