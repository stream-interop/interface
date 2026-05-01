<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_ClosableStream_][] extends [_Stream_][] to afford closing the stream.
 *
 * - Directives:
 *
 *     - Implementations MAY close the encapsulated resource internally
 *       without affording [_ClosableStream_][].
 *
 * - Notes:
 *
 *     - **Not all [_Stream_][] implementations need to be closable.** It may
 *       be important for resource closing to be handled by a separate service
 *       or authority, and not be closable by [_Stream_][] consumers.
 */
interface ClosableStream extends Stream
{
    /**
     * Closes the encapsulated resource as if by [`fclose()`][],
     * [`pclose()`][], etc.
     *
     * - Directives:
     *
     *     - Implementations MUST throw a [_StreamThrowable_][] on failure.
     *
     * @throws StreamThrowable on failure.
     */
    public function close() : void;
}
