# PHP Consul API ACL

All interactions with the [`v1/acl`](https://www.consul.io/docs/agent/http/acl.html) endpoint are done
via the [ACLClient](../src/ACL/ACLClient.php) class.

If you have constructed a [Consul](../src/Consul.php) object, this is done as so:

```php
$acl = $consul->ACL;
```