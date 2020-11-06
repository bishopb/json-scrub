<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ObjectScrubTest extends TestCase
{
    /**
     * @dataProvider provides_keys_and_objects_and_the_result
     */
    public function testScrub($message, $keys, $object, $replacement, $expected)
    {
        $actual = (new ObjectScrub)->scrub($keys, $object);
        $this->assertSame($expected, $actual, $message);
    }

    public static function provides_keys_and_objects_and_the_result()
    {
        return [
            [
                'empty keys and object should have no scrubbing',
                [ ], [ ], 'X', [ ],
            ],
            [
                'empty keys and non-empty object should have no scrubbing',
                [ ], [ 'foo' => 'bar' ], 'X', [ 'foo' => 'bar' ],
            ],
            [
                'non-intersecting keys on non-empty object should have no scrubbing',
                [ 'quux' ], [ 'foo' => 'bar' ], 'X', [ 'foo' => 'bar' ],
            ],
            [
                'intersecting key at top-level on non-empty object should scrub',
                [ 'foo' ], [ 'foo' => 'bar' ], 'X', [ 'foo' => 'X' ],
            ],
            [
                'intersecting key at mid-level on non-empty object should scrub',
                [ 'foo.bar' ], [ 'foo' => [ 'bar' => 'baz' ] ], 'X', [ 'foo' => [ 'bar' => 'X' ] ],
            ],
            [
                'non-intersecting key at mid-level on non-empty object not should scrub',
                [ 'foo.quux' ], [ 'foo' => [ 'bar' => 'baz' ] ], [ 'foo' => [ 'bar' => 'baz' ] ],
            ],
        ];
    }
}
