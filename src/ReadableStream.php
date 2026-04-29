<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_ReadableStream_][] affords non-idempotent reading from the stream.
 *
 * - Directives:
 *
 *     - If the encapsulated resource is not readable at the time it becomes
 *       available to the [_ReadableStream_][], implementations MUST throw a
 *       [_StreamThrowable_][].
 */
interface ReadableStream extends Stream
{
    /**
     * Tests for end-of-file on the encapsulated resource as if by [`feof()`][].
     *
     * @throws StreamThrowable on failure.
     */
    public function eof() : bool;

    /**
     * Returns up to $length bytes from the encapsulated resource as if by
     * [`fread()`][].
     *
     * @param int<1,max> $length
     * @throws StreamThrowable on failure.
     */
    public function read(int $length) : string;

    /**
     * Returns the remaining contents of the resource from the current pointer
     * position as if by [`stream_get_contents()`][].
     *
     * @throws StreamThrowable on failure.
     */
    public function getContents() : string;
}
