<?php
declare(strict_types=1);

namespace StreamInterop\Interface;

/**
 * [_SizableStream_][] extends [_Stream_][] to afford getting the full length of
 * the stream in bytes.
 *
 * - Directives:
 *
 *     - Implementations MAY get the size of the encapsulated resource
 *       internally without affording [_SizableStream_][].
 *
 * - Notes:
 *
 *     - **Not all [_Stream_][] implementations need to be sizable.** Some
 *       encapsulated resources may be unable to report a size; for example,
 *       remote or write-only resources.
 */
interface SizableStream extends Stream
{
    /**
     * Returns the length of the encapsulated resource in bytes as if by the
     * [`fstat()`][] value for `size`, or `null` if indeterminate or on error.
     *
     * @return ?int<0,max>
     */
    public function getSize() : ?int;
}
