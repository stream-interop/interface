<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

use Stringable;

/**
 * [_WritableStream_][] extends [_Stream_][] to afford writing to the stream at
 * the current pointer position.
 *
 * - Directives:
 *
 *     - If the encapsulated resource is not writable at the time it becomes
 *       available to the [_WritableStream_][], implementations MUST throw a
 *       [_StreamThrowable_][].
 *
 *     - Implementations MAY write to the encapsulated resource internally
 *       without affording [_WritableStream_][].
 */
interface WritableStream extends Stream
{
    /**
     * Writes `$data` starting at the current stream pointer position, returning
     * the number of bytes written, as if by [`fwrite()`][].
     *
     * - Directives:
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @throws StreamThrowable on failure.
     */
    public function write(string|Stringable $data) : int;
}
