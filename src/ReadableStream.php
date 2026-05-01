<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_ReadableStream_][] extends [_Stream_][] to afford non-idempotent reading
 * from the stream.
 *
 * - Directives:
 *
 *     - If the encapsulated resource is not readable at the time it becomes
 *       available to the [_ReadableStream_][], implementations MUST throw a
 *       [_StreamThrowable_][].
 *
 *     - Implementations MAY read from the encapsulated resource internally
 *       without affording [_ReadableStream_][].
 *
 * - Notes:
 *
 *     - **The `eof()` method is on [_ReadableStream_][], not [_Stream_][] or
 *       [_SeekableStream_][].** End-of-file is determined as a function of
 *       reading past the end of the file, not as of seeking to the end of
 *       the file. Cf. <https://www.php.net/manual/en/function.feof.php#122925>.
 *
 *     - **These methods are non-idempotent.** They may return different
 *       results on repeated sequential calls, and may have side effects (e.g.,
 *       changing the position of the pointer.)
 */
interface ReadableStream extends Stream
{
    /**
     * Tests for end-of-file on the encapsulated resource as if by [`feof()`][].
     *
     * - Directives:
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @throws StreamThrowable on failure.
     */
    public function eof() : bool;

    /**
     * Returns up to `$length` bytes from the encapsulated resource as if by
     * [`fread()`][].
     *
     * - Directives:
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @param int<1,max> $length
     * @throws StreamThrowable on failure.
     */
    public function read(int $length) : string;

    /**
     * Returns the remaining contents of the resource from the current pointer
     * position as if by [`stream_get_contents()`][].
     *
     * - Directives:
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @throws StreamThrowable on failure.
     */
    public function getContents() : string;
}
