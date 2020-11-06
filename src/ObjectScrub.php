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
     * @param array $keys A description of the keys to scrub.
     * @param array $source The object to scrub.
     * @param string $replacement The string to replace the scalar matches by.
     * @return array The scrubbed object.
     */
    public function scrub(array $keys, array $source, string $replacement)
    {
        foreach ($keys as $key) {
            $path = explode('.', $key);
            $node = &$source;
            while ($next = array_shift($path)) {
                if (! array_key_exists($next, $node)) {
                    break;
                }
                $node = &$node[$next];
                if (! is_array($node)) {
                    $node = $replacement;
                    break;
                } else if (! count($path)) {
                    $this->replaceAllScalars($node, $replacement);
                    break;
                }
            }
        }
        return $source;
    }

    /**
     * Traverse a given object, looking for the named keys. If found, regardless
     * of depth, replace their values with the given replacement.
     *
     * @param array $keys The keys to scrub.
     * @param array $source The object to scrub.
     * @param string $replacement The string to replace the scalar matches by.
     * @return array The scrubbed object.
     */
    public function scrubAll(array $keys, array $source, string $replacement)
    {
        foreach ($source as $key => $value) {
            if (in_array($key, $keys)) {
                if (is_scalar($value)) {
                    $source[$key] = $replacement;
                } else {
                    $this->replaceAllScalars($source[$key], $replacement);
                }
            } else if (is_array($value)) {
                $source[$key] = $this->scrubAll($keys, $source[$key], $replacement);
            }
        }
        return $source;
    }

    /**
     * Replace all scalar values in the given array with the given string.
     *
     * @param array $source The object to scrub.
     * @param string $replacement The string to replace the scalar values by.
     * @return void
     */
    private function replaceAllScalars(array &$node, string $replacement)
    {
        foreach ($node as $key => $value) {
            if (is_scalar($value)) {
                $node[$key] = $replacement;
            } else if (is_array($value)) {
                $this->replaceAllScalars($node[$key], $replacement);
            }
        }
    }
}
