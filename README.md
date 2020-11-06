# json-scrub
Sanitize arbitrary JSON objects according to a configuration.

## Quickstart

```sh
$ composer require bishopb/json-scrub
$ composer test
```

## Using

There are two ways to use this code: library and command line.

### Library

The library routine is in `ObjectScrub::scrub`:

```php
  /**
   * Traverse a given object, looking for keys matching the given
   * specification. If the object contains a matching key, replace the value
   * pointed to by that key with a replacement string.
   *
   * Key specification is given in dot notation, where each dot represents
   * descent. Eg, "foo.bar" would match a key in [ "foo" => [ "bar" => "baz" ] ]
   *
   * @param array $keys A description of the keys to scrub.
   * @param array $source The object to scrub.
   * @return array The scrubbed object.
   */
  public function scrub(array $keys, array $source, $replacement);
```

To use this:

```php
$scrubber = new \ObjectScrub();
$objects = $scrubber->scrub($keys, $objects, '***');
```

Refer to the `tests/` directory for more example usages.

### Command line usage

The command `sanitize` takes up to three arguments, as described in the built-in
documentation:

```sh
$ ./sanitize -h
Scrub a file containing one or more JSON objects of sensitive values.

USAGE:
    sanitize [config='./config.json'] [objects='./objects.json'] [replace='***']

WHERE:
    config   Is a file describing the keys to be scrubbed. Defaults to
             './config.json'. See the config.json for a description of the file.
    objects  Is a file containing the objects to to be scrubbed. Keys in those
             objects matching keys in the config file will be replaced with the
             given replacement. Default is './objects.json'.
    replace  The string to replace matching keys with. Defaults to '***'.

OUTPUT:
    Outputs on standard out the scrubbed version of each JSON object from the
    given objects, each object separated by a newline. The original white space
    may not be preserved.

EXIT CODES:
    0 if everything is ok
    1 if incorrect arguments are given
    2 if the files pointed to by the arguments cannot be read
    3 if the files do not contain valid JSON
    4 if the configuration file does not have a recognized format
```

See the [`examples/`][ex] directory for some possible usages.

[ex]:./examples/
