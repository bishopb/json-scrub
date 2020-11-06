<?php declare(strict_types=1);

class ObjectScrub
{
    /**
     * Traverse a given object, looking for keys matching the given
     * specification. If the object contains a matching key, replace the value
     * pointed to by that key with a replacement string.
     *
     * Key specification is given in dot notation, where each dot represents
     * descent. Eg, "foo.bar" would match a key in [ "foo" => [ "bar" => "baz" ] ]
     *
     * Key specification may also include patterns, expressed as [glob][1]. For
     * example, "fo*" would match keys "foo" and "fob".
     *
     * [1]:https://www.php.net/manual/en/function.fnmatch.php
     *
     * @param array $keys A description of the keys to scrub.
     * @param array $source The object to scrub.
     * @return array The scrubbed object.
     */
    public function scrub(array $keys, array $source, $replacement)
    {
        return $source;
    }
}
