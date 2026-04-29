<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

use Stringable;

/**
 * [_StringableStream_][] affords idempotent reading from the stream.
 *
 * - Directives:
 *
 *     - If the encapsulated resource is not readable at the time it becomes
 *       available to the [_StringableStream_][], implementations MUST throw a
 *       [_StreamThrowable_][].
 *
 *     - If the encapsulated resource is not seekable at the time it becomes
 *       available to the [_StringableStream_][], implementations MUST throw a
 *       [_StreamThrowable_][].
 */
interface StringableStream extends Stream, Stringable
{
    /**
     * Returns the entire contents of the encapsulated resource as if by
     * [`rewind()`][]ing before returning [`stream_get_contents()`][].
     *
     * - Directives:
     *
     *     - Implementations MUST reposition the encapsulated resource pointer
     *       to its initial location.
     *
     * @throws StreamThrowable on failure.
     */
    public function __toString() : string;

    /**
     * Returns a string from the resource as if by seeking to an offset before
     * returning up to a certain number of bytes.
     *
     * - Directives:
     *
     *     - If the $offset is negative, implementations MUST begin reading at
     *       that many bytes from the end of the stream; otherwise,
     *       implementations MUST begin reading at that many bytes from the
     *       start of the stream.
     *
     *     - If the $length is null, implementations MUST return all remaining
     *       bytes from the stream; otherwise, implementations MUST return up to
     *       that many bytes from the stream.
     *
     *     - Implementations MUST reposition the encapsulated resource pointer
     *       to its initial location.
     *
     * @param ?int<0,max> $length
     * @throws StreamThrowable on failure.
     */
    public function subString(int $offset, ?int $length = null) : string;
}
