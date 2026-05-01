<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

use Stringable;

/**
 * [_StringableStream_][] extends [_Stream_][] to afford idempotent reading from
 * the encapsulated resource.
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
 *
 *     - Implementations MAY convert all or part of the encapsulated resource
 *       to a string internally without affording [_StringableStream_][].
 *
 * - Notes:
 *
 *     - **These methods are idempotent.** Repeated sequential calls to an
 *       unchanged resource will return the exact same result, without exposing
 *       side effects (such as the pointer position being changed).
 */
interface StringableStream extends Stream, Stringable
{
    /**
     * Returns the entire contents of the encapsulated resource as if by
     * [`rewind()`][]ing before returning [`stream_get_contents()`][].
     *
     * - Directives:
     *
     *     - After reading, implementations MUST reposition the encapsulated
     *       resource pointer to its initial location.
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @throws StreamThrowable on failure.
     */
    public function __toString() : string;

    /**
     * Returns a string from the encapsulated resource as if by [`fseek()`][]ing
     * before reading.
     *
     * - Directives:
     *
     *     - If the `$offset` is negative, implementations MUST begin reading at
     *       that many bytes from the end of the stream; otherwise,
     *       implementations MUST begin reading at that many bytes from the
     *       start of the stream.
     *
     *     - If the `$length` is null, implementations MUST return all remaining
     *       bytes from the stream; otherwise, implementations MUST return up to
     *       that many bytes from the stream.
     *
     *     - After reading, implementations MUST reposition the encapsulated
     *       resource pointer to its initial location.
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @param ?int<0,max> $length
     * @throws StreamThrowable on failure.
     */
    public function subString(int $offset, ?int $length = null) : string;
}
