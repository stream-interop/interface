<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

use Generator;
use Stringable;

/**
 * If the encapsulated resource is not readable at the time it becomes
 * available to the StringableStream, the implementation MUST throw
 * LogicException (or an extension thereof).
 *
 * If the encapsulated resource is not seekable at the time it becomes
 * available to the StringableStream, the implementation MUST throw
 * LogicException (or an extension thereof).
 */
interface StringableStream extends Stream, Stringable
{
    /**
     * Returns the entire contents of the encapsulated resource as if by
     * rewind()ing before returning stream_get_contents().
     *
     * The implementation MUST reposition the encapsulated resource pointer to
     * its initial location.
     *
     * The implementation MUST throw RuntimeException (or an extension thereof)
     * on failure.
     */
    public function __toString() : string;

    /**
     * Returns a string from the resource as if by seeking to an offset before
     * returning up to a certain number of bytes.
     *
     * If the `$offset` is negative, the implementation MUST begin reading at
     * that many bytes from the end of the stream; otherwise, the implementation
     * MUST begin reading at that many bytes from teh start of the stream.
     *
     * If the `$length` is null, the implementation MUST return all remaining
     * bytes from the stream; otherwise, the implementation MUST return up to
     * that many bytes from the stream.
     *
     * The implementation MUST reposition the encapsulated resource pointer to
     * its initial location.
     *
     * The implementation MUST throw RuntimeException (or an extension thereof)
     * on failure.
     *
     * @param ?int<0,max> $length
     */
    public function subString(int $offset, ?int $length = null) : string;
}
