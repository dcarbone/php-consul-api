# PHP Consul API Configuration

This class is modeled quite closely after the
[Config Struct](https://github.com/hashicorp/consul/blob/master/api/api.go#L101) present in the 
[Consul API Subpackage](https://github.com/hashicorp/consul/tree/master/api).

The primary difference is that the HTTP client is not stored within the config object.  This is a possible future
feature.

## Default Configuration

If you have defined some of the [Consul Environment Variables](https://www.consul.io/docs/agent/options.html)
on your hosts then it would probably be easiest to simply execute the following:

```php
$config = \DCarbone\PHPConsulAPI\Config::newDefaultConfig();
```

You can see what this ultimately does [in the method definition](../src/Config.php#L47).
 
## Advanced Configuration

You may alternatively define values yourself:

```php
$config = new \DCarbone\PHPConsulAPI\Config([
    'Address' => 'address of server', // REQUIRED 
    'Scheme' => 'http or https', // REQUIRED
    'Datacenter' => 'name of datacenter', // OPTIONAL
    'HttpAuth' => 'user:pass', // OPTIONAL,
    'WaitTime' => 30, // OPTIONAL, not used yet
    'Token' => 'auth token', // OPTIONAL
    'CAFile' => 'path to ca', // OPTIONAL
    'CertFile' => 'path to cert', // OPTIONAL
    'KeyFile' => 'path to key', // OPTIONAL
    'InsecureSkipVerify' => false, // OPTIONAL
]);
```