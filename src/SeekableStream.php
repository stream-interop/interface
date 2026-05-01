<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

use const SEEK_CUR;
use const SEEK_END;
use const SEEK_SET;

/**
 * [_SeekableStream_][] extends [_Stream_][] to afford moving the stream pointer
 * position back and forth.
 *
 * - Directives:
 *
 *     - If the encapsulated resource is not seekable at the time it becomes
 *       available to the [_SeekableStream_][], implementations MUST throw a
 *       [_StreamThrowable_][].
 */
interface SeekableStream extends Stream
{
    /**
     * Moves the stream pointer position to the beginning of the stream as if
     * by [`rewind()`][].
     *
     * - Directives:
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @throws StreamThrowable on failure.
     */
    public function rewind() : void;

    /**
     * Moves the stream pointer position to the `$offset` as if by [`fseek()`][].
     *
     * - Directives:
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @param SEEK_CUR|SEEK_SET|SEEK_END $whence
     * @throws StreamThrowable on failure.
     */
    public function seek(int $offset, int $whence = SEEK_SET) : void;

    /**
     * Returns the current stream pointer position as if by [`ftell()`][].
     *
     * - Directives:
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @throws StreamThrowable on failure.
     */
    public function tell() : int;
}
