<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ObjectScrubTest extends TestCase
{
    /**
     * @dataProvider provides_cases_for_scrub
     */
    public function testScrub($message, $keys, $object, $replacement, $expected)
    {
        $actual = (new ObjectScrub)->scrub($keys, $object, $replacement);
        $this->assertSame($expected, $actual, $message);
    }

    public static function provides_cases_for_scrub()
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
                [ 'foo.quux' ], [ 'foo' => [ 'bar' => 'baz' ] ], 'X', [ 'foo' => [ 'bar' => 'baz' ] ],
            ],
            [
                'scrub recursively if the key points to a object',
                [ 'foo' ], [ 'foo' => [ 'baz', 'baz' => [ 'quux' ] ] ], 'X', [ 'foo' => [ 'X', 'baz' => [ 'X' ] ] ],
            ],
        ];
    }

    /**
     * @dataProvider provides_cases_for_scrub_all
     */
    public function testScrubAll($message, $keys, $object, $replacement, $expected)
    {
        $actual = (new ObjectScrub)->scrubAll($keys, $object, $replacement);
        $this->assertSame($expected, $actual, $message);
    }

    public static function provides_cases_for_scrub_all()
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
                [ 'bar' ], [ 'foo' => [ 'bar' => 'baz' ] ], 'X', [ 'foo' => [ 'bar' => 'X' ] ],
            ],
            [
                'intersecting key at any level more than once on non-empty object should scrub',
                [ 'bar' ], [ 'foo' => [ 'bar' => [ 'quux' ] ], 'bar' => 'quux' ], 'X', [ 'foo' => [ 'bar' => [ 'X' ] ], 'bar' => 'X' ],
            ],
        ];
    }
}
