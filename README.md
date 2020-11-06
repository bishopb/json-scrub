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

There are two library functions for use:

1. `ObjectScrub::scrubAll` replaces the value of all instances of the given
   keys, regardless of where they appear in the given object.
1. `ObjectScrub::scrub` replaces the value for all keys that match the _path_
   given, in dot notation. So `foo.bar` would replace the value "baz" in this:
   `{ "foo": { "bar": "baz" }, "foo.bar": "quux"  }`.

For example:

```php
$scrubber = new \ObjectScrub();
$objects = $scrubber->scrub([ 'foo.bar' ], $objects, '***');
$objects = $scrubber->scrubAll([ 'foo' ], $objects, '***');
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
