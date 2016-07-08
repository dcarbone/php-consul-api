# PHP Consul API KV

All interactions with the [`v1/kv`](https://www.consul.io/docs/agent/http/kv.html) endpoint are done
via the [KVClient](./src/KV/KVClient.php) class.

If you have constructed a [Client](./src/Client.php) object, this is done as so:

```php
/** @var \DCarbone\PHPConsulAPI\KV\KVClient $kv */
$kv = $client->KV();
```

## Actions

### Key Getting

```php
list($kvp, $qm, $err) = $client->KV()->get('prefix/keyname');
if (null !== $err)
    die($err);

var_dump($qm, $kvp);
```

### Key Listing


*All Keys*:
```php
list($keys, $qm, $err) = $client->KV()->keys();
if (null !== $err)
    die($err);

var_dump($qm, $keys);
```

*Only Keys with Specific Prefix*
```php
list($keys, $qm, $err) = $client->KV()->keys('prefix');
if (null !== $err)
    die($err);

var_dump($qm, $keys);
```

### Key Putting

```php
$kvp = new KVPair(['Key' => 'prefix/mykey', 'Value' => 'my value']);
list($wm, $err) = $client->KV()->put($kvp);
if (null !== $err)
    die($err);

var_dump($wm);
```

### Key Deletion

```php
list($wm, $err) = $client->KV()->delete('prefix/mykey');
if (null !== $err)
    die($err);
var_dump($wm);

```

### Tree Retrieval

This is a custom feature to allow `get-tree`-like functionality with 0.6.4 (the current release as of this writing)

This call executes a `$client->KV()->keys()` call, parses the results, and builds an object tree of
[KVTree](./src/KV/KVTree.php) and [KVPair](./src/KV/KVPair.php) objects.

*Entire Tree - POTENTIALLY VERY SLOW!!!!!!*
```php
list($tree, $err) = $client->KV()->tree();
if (null !== $err)
    die($err);

```

*Only Tree's under Specific Root Prefix*

```php
list($tree, $err) = $client->KV()->tree('prefix');
if (null !== $err)
    die($err);
```

You may use the below little helper script to format the output for testing

```php
function output_tree(\DCarbone\PHPConsulAPI\KV\KVTree $tree)
{
    foreach($tree as $item)
    {
        if ($item instanceof \DCarbone\PHPConsulAPI\KV\KVTree)
        {
            echo '<li>';
            echo $item->getPrefix();
            echo '<br>';
            echo '<ul>';
            output_tree($item);
            echo '</ul>';
            echo '</li>';
        }
        else if ($item instanceof \DCarbone\PHPConsulAPI\KV\KVPair)
        {
            echo '<li>'.$item->getKey().'</li>';
        }
    }
}

echo '<ul>';
foreach($tree as $v)
{
    if ($v instanceof \DCarbone\PHPConsulAPI\KV\KVTree)
    {
        echo '<li>';
        echo $v->getPrefix();
        echo '<br>';
        echo '<ul>';
        output_tree($v);
        echo '</ul>';
        echo '</li>';
    }
    else if ($v instanceof \DCarbone\PHPConsulAPI\KV\KVPair)
    {
        echo '<li>'.$v->getKey().'</li>';
    }
}
echo '</ul>';
```

The above will output something along the following (depends entirely on the keys you set):

```html
<ul>
    <li>dcarbone/<br>
        <ul>
            <li>dcarbone/key2</li>
            <li>dcarbone/second key</li>
            <li>dcarbone/testkey</li>
        </ul>
    </li>
</ul>
```