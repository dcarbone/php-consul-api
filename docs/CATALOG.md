# PHP Consul API Catalog

*This class is still under heavy development*

All interactions with the [`v1/catalog`](https://www.consul.io/docs/agent/http/catalog.html) endpoint are done
via the [CatalogClient](./src/Catalog/CatalogClient.php) class.

If you have constructed a [Client](./src/Client.php) object, this is done as so:

```php
$catalog = $client->Catalog();
```

## Actions

###